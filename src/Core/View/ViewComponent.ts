import '@ungap/custom-elements'
import 'simpledotcss';
import "./ViewComponent.scss"
import "./Analytics"
import "./Component/Button/ConfirmButton"
import "./ReactiveDetails"
import "./Component/Form/ReactiveForm"
import Toast from "./Component/Toast/Toast";

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

document.addEventListener('click', event => {
    const anchor = (event.target as HTMLElement).closest('a');
    if (anchor?.target === 'popup' && anchor.href) {
        event.preventDefault()
        window.open(anchor.href, '_blank', 'popup,width=800,height=600')
    }
})

