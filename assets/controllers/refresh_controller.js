import { Controller } from 'stimulus';

export default class extends Controller {
  static refresh() {
    const { location } = window;
    window.location = location;
  }
}
