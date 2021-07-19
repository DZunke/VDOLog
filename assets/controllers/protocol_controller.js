import { Controller } from 'stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  connect() {
    const that = this;

    this.constructor.focusguard();

    document.querySelectorAll('input[type="text"], textarea')
      .forEach((elem) => {
        elem.addEventListener('keydown', (e) => {
          if (e.ctrlKey && e.keyCode === 13) { // STRG + Enter
            that.submit();
          }

          if (e.ctrlKey && e.keyCode === 67) { // STRG + C
            that.reset();
          }
        });
      });
  }

  static focusguard() {
    document.querySelector('input[tabindex="1"]')
      .focus();
  }

  submit() {
    document.querySelector(this.formQuery)
      .submit();
  }

  reset() {
    document.getElementById('protocol_parent').value = '';

    const removeElement = document.getElementById('protocol-add-child-info');
    if (removeElement !== null) {
      removeElement.remove();
      document.getElementById('protocol-add-child-reset')
        .remove();
      document.querySelectorAll('.protocol-add-child-highlite')
        .forEach((elem) => {
          elem.classList.remove('protocol-add-child-highlite');
        });
    }

    document.querySelectorAll('input, textarea')
      .forEach((elem) => {
        elem.setAttribute('value', '');
      });
    document.querySelectorAll('span.invalid-feedback')
      .forEach((elem) => {
        elem.remove();
      });
    document.querySelectorAll('.is-invalid')
      .forEach((elem) => {
        elem.classList.remove('is-invalid');
      });

    this.constructor.focusguard();
  }

  addChildEntry($event) {
    if ($event === undefined) {
      return;
    }

    $event.preventDefault();
    this.reset();

    let $element = $event.target;
    if ($event.target.nodeName === 'I') {
      $element = $event.target.parentNode;
    }

    const $id = $element.dataset.id;
    const $highliteClass = $element.dataset.highlite;
    const $idFormElement = document.getElementById('protocol_parent');

    if ($id === undefined) {
      return;
    }

    $idFormElement.value = $id;

    if ($highliteClass !== undefined && $highliteClass !== '') {
      $element.closest(`.${$highliteClass}`)
        .classList
        .add('protocol-add-child-highlite');
    }

    const formAppendingModeInfo = document.createElement('p');
    formAppendingModeInfo.id = 'protocol-add-child-info';
    formAppendingModeInfo.setAttribute('data-append', $id);
    formAppendingModeInfo.className = 'text-center';
    formAppendingModeInfo.innerHTML = '<strong>Anfügenmodus</strong>';
    $idFormElement.parentElement.prepend(formAppendingModeInfo);

    const formAppendingCancelButton = document.createElement('button');
    formAppendingCancelButton.id = 'protocol-add-child-reset';
    formAppendingCancelButton.type = 'button';
    formAppendingCancelButton.className = 'btn btn-block btn-secondary';
    formAppendingCancelButton.tabIndex = 4;
    formAppendingCancelButton.innerText = 'Abbrechen';

    formAppendingCancelButton.addEventListener('click', (event) => {
      event.preventDefault();
      this.reset();
    });

    $idFormElement.parentElement.append(formAppendingCancelButton);

    this.constructor.focusguard();
  }

  get formQuery() {
    return this.data.get('formQuery');
  }
}
