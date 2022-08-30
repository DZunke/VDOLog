import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  refresh() { // eslint-disable-line class-methods-use-this
    const { location } = window;
    window.location = location;
  }
}
