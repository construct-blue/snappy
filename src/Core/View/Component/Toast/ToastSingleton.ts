import ToastMessage from "./ToastMessage";

export class ToastSingleton extends HTMLElement {
    private static _instance: ToastSingleton

    public constructor() {
        super()
        this.classList.add('toast')
        document.body.append(this)
    }

    public display(text: string, type: string, icon: null | string) {
        if (null === icon) {
            if (type == 'error') {
                icon = 'alert-circle'
            } else if (type == 'success') {
                icon = 'check-circle'
            } else {
                icon = 'info';
            }
        }

        const message = new ToastMessage(icon, type, text);
        this.append(message.element)
    }

    static get instance(): ToastSingleton {
        if (undefined === this._instance) {
            this._instance = new ToastSingleton()
        }
        return this._instance
    }
}

customElements.define('core-toast', ToastSingleton)

