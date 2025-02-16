import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];

    async openModal(event) {
        const modal = new Modal(this.modalTarget);
        modal.show();
    }
}
