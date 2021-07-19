import { Controller } from 'stimulus';

export default class extends Controller {
  featureEnabled = false;

  notificationsEnabled = false;

  defaultNotifcicationOptions = {
    body: '',
    icon: this.iconUrl,
  };

  defaultNotificationOnClickAtion = function () {
    window.focus();
    this.close();
  };

  connect() {
    if (!('Notification' in window)) {
      this.featureEnabled = false;
      document.getElementById(this.askPermissionButton)
        .remove();
      return;
    }

    this.featureEnabled = true;
    const that = this;

    if (Notification.permission === 'default') {
      this.askPermission();
    }

    if (Notification.permission === 'granted') {
      this.activatePermissionButton();
      that.fetchNotifications();

      window.setInterval(() => {
        // that.fetchNotifications();
      }, 5000);
    }
  }

  async fetchNotifications() {
    const that = this;

    await fetch('https://vdolog.local:8081/checklist/notify')
      .then((response) => response.json())
      .then((data) => {
        if (data.messages.length === 0) {
          return;
        }

        data.messages.forEach((value) => {
          that.sendNotification('Offene ToDos', value);
        });
      });
  }

  askPermission(e) {
    if (e !== undefined) {
      e.preventDefault();
    }

    if (this.featureEnabled === false) {
      return;
    }

    const that = this;

    if (Notification.permission === 'granted') {
      that.activatePermissionButton();
      that.sendNotification(
        'Berechtigungsprüfung',
        'Benachrichtigungen können empfangen werden',
      );

      return;
    }

    Notification.requestPermission()
      .then((permission) => {
        // If the user accepts, let's create a notification
        if (permission === 'granted') {
          that.sendNotification(
            'Berechtigungsprüfung',
            'Benachrichtigungen können empfangen werden',
          );
          that.activatePermissionButton();
        }
      });
  }

  sendNotification(title, message) {
    if (this.featureEnabled === false) {
      return;
    }

    if (Notification.permission === 'default') {
      this.askPermission();

      if (Notification.permission === 'denied') {
        return;
      }
    }

    const notificationOptions = { ...this.defaultNotifcicationOptions };
    notificationOptions.body = message;
    const notification = new Notification(title, notificationOptions);
    notification.onclick = this.defaultNotificationOnClickAtion;
  }

  activatePermissionButton() {
    if (this.featureEnabled === false) {
      return;
    }

    const button = document.getElementById(this.askPermissionButton);
    button.classList.remove('text-red');
    button.classList.add('text-green');
  }

  get askPermissionButton() {
    return this.data.get('ask-permission-button');
  }

  get iconUrl() {
    return this.data.get('icon-url');
  }
}
