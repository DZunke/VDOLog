import { Controller } from 'stimulus';

export default class extends Controller {
  static refresh(e) {
    e.preventDefault();
    const { location } = window;
    window.location = location;
  }
}
