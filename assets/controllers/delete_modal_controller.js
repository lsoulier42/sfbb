import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['confirm'];

    connect() {
        this.element.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            if (!trigger) {
                return;
            }
            this.confirmTarget.dataset.url = trigger.dataset.bsRoute;
            this.confirmTarget.dataset.csrf = trigger.dataset.csrf;
        });
    }

    confirm(event) {
        event.preventDefault();
        const button = this.confirmTarget;
        const formData = new FormData();
        formData.append('token', button.dataset.csrf);
        fetch(button.dataset.url, {
            method: 'POST',
            body: formData,
        })
            .catch(() => {})
            .finally(() => window.location.reload());
    }
}
