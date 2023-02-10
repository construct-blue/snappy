import '@ungap/custom-elements'
import 'simpledotcss';
import "./ViewComponent.scss"
import "./Component/Button/ConfirmButton"
import "./Component/Details/ReactiveDetails"
import "./Component/Form/ReactiveForm"
import Toast from "./Component/Toast/Toast";
import Popup from "./Popup";

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

window.addEventListener('message', e => {
    if (e.data.popup) {
        Popup.open(e.data.popup)
    }
    if (e.data.remove) {
        document.querySelector(e.data.remove)?.remove()
    }
})

window.addEventListener('click', event => {
    const anchor = (event.target as HTMLElement).closest('a');
    if (!anchor?.href) {
        return;
    }
    if (window.top && window.self !== window.top) {
        event.preventDefault();
        window.top.postMessage({popup: anchor.href}, '*');
    } else if (anchor?.target === 'popup') {
        event.preventDefault()
        Popup.open(anchor.href)
    }
})

