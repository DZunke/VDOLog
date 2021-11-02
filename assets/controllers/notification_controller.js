import { Controller } from 'stimulus';

export default class extends Controller {
  featureEnabled = false;

  notificationsEnabled = false;

  xhrPollingTimeout = 30000; // Every 30 seconds

  lastCheckDate = null;

  defaultNotifcicationOptions = {
    body: '',
    icon: this.iconUrl,
  };

  defaultNotificationOnClickAction = () => {
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

    this.lastCheckDate = (new Date()).toISOString();

    if (Notification.permission === 'default') {
      this.askPermission();
    }

    if (Notification.permission === 'granted') {
      this.activatePermissionButton();
      that.fetchNotifications();
      that.fetchReminder();

      window.setInterval(() => {
        // that.fetchNotifications();
        // that.fetchReminder();
      }, this.xhrPollingTimeout);
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

  async fetchReminder() {
    const that = this;

    await fetch(`https://vdolog.local:8081/game/reminder/remind/${that.lastCheckDate}`)
      .then((response) => response.json())
      .then((data) => {
        that.lastCheckDate = (new Date()).toISOString();
        if (data.messages.length === 0) {
          return;
        }

        data.messages.forEach((value) => {
          that.sendNotification(value.title, value.message);
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
    notification.onclick = this.defaultNotificationOnClickAction;
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
