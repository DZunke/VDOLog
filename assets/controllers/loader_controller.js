import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  #activeLoaderId = 'active-loader-element'

  connect() {
    const that = this;

    window.onbeforeunload = (e) => {
      const $srcElement = e.target.activeElement;
      that.check($srcElement);
    };
    window.onpageshow = () => that.unload();
  }

  check($element) {
    const $existingLoader = document.getElementById(this.activeLoaderId);
    if ($existingLoader !== null) {
      return;
    }

    if ($element !== undefined && $element.classList.contains('no-loader')) {
      return;
    }

    if ($element !== undefined) {
      const $checkForm = $element.getAttribute('data-check-form');
      if ($checkForm !== null) {
        const $form = document.querySelector(`form[name="${$checkForm}"]`);
        if ($form === null || $form.noValidate || $form.checkValidity()) {
          this.load();
        }

        return;
      }
    }

    this.load();
  }

  load() {
    if (document.getElementById(this.activeLoaderId) !== null) {
      return;
    }

    const $template = document.getElementById(this.templateId);
    const $loaderElement = $template.content.cloneNode(true);
    $loaderElement.querySelector('div').id = this.activeLoaderId;

    document.body.append($loaderElement);
  }

  unload() {
    const $element = document.getElementById(this.activeLoaderId);
    if ($element !== null) {
      $element.remove();
    }
  }

  get templateId() {
    return this.data.get('templateId');
  }

  get activeLoaderId() {
    return this.#activeLoaderId;
  }
}
