import './TeslaSetup.scss'

class TeslaSetup extends HTMLFormElement {
    constructor() {
        super()
        this.onsubmit = event => {
            const formData = new FormData(this)
            if ((formData.get('url') as string).trim().length == 0) {
                event.preventDefault();
                const popup = window.open(this.dataset.popupUrl, '_blank', 'noreferre,noopener')
                popup?.focus()
            }
        }
    }
}

customElements.define('tesla-setup', TeslaSetup, {extends: 'form'})