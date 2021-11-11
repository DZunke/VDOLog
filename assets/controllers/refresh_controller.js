import { Controller } from 'stimulus';

export default class extends Controller {
  refresh() { // eslint-disable-line class-methods-use-this
    const { location } = window;
    window.location = location;
  }
}
