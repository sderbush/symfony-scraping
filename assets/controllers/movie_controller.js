import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.querySelector('select').addEventListener('change', function(e) {
            this.form.submit();
        });
    }
}
