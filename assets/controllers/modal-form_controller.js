import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];
    static values = {
        formUrl: String,
    }

    async openModal(event) {
        this.modalBodyTarget.innerHTML = 'Loading..';

        const response = await fetch(`${this.formUrlValue}`);
        this.modalBodyTarget.innerHTML = await response.text();

        const modal = new Modal(this.modalTarget);
        modal.show();
    }
    async submitForm(event) {
        const form = this.modalBodyTarget.querySelectorAll(`:scope ${'form'}`)[0];
        const formSerialized = new URLSearchParams(new FormData(form)).toString();

        const response = await fetch(`${this.formUrlValue}`, {
            method: 'POST',
            body: formSerialized,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        this.modalBodyTarget.innerHTML = await response.text();
    }
}
