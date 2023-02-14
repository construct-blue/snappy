import Toast from "./Toast/Toast";

customElements.define('toast-messages', class extends HTMLScriptElement {
    constructor() {
        super();
        if (this.textContent) {
            const messages = JSON.parse(this.textContent);
            for (const type in messages) {
                for (const text of messages[type]) {
                    Toast.display(text, type);
                }
            }
        }
    }
}, {extends: 'script'});

customElements.define('toast-validations', class extends HTMLScriptElement {
    constructor() {
        super();
        if (this.textContent) {
            const validations = JSON.parse(this.textContent);
            for (const field in validations) {
                for (const text of validations[field]) {
                    Toast.display(text, 'error');
                }
            }        }
    }
}, {extends: 'script'});
