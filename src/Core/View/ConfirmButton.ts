class ConfirmButton extends HTMLButtonElement{
    private originalContent: string;
    private readonly message: string;

    get submittable(): boolean {
        return this.hasAttribute('submittable')
    }

    set submittable(state: boolean) {
        this.toggleAttribute('submittable', state)
        if (state) {
            this.style.backgroundColor = '#921919';
            this.style.color = '#dcdcdc'
            this.originalContent = this.innerHTML;
            this.innerText = this.message;
        } else if (this.originalContent) {
            this.style.backgroundColor = '';
            this.style.color = '';
            this.innerHTML = this.originalContent;
        }

    }

    constructor() {
        super();
        this.message = this.getAttribute('message') ?? 'confirm?'
        this.submittable = this.submittable;

        this.onclick = event => {
            if (this.submittable) {
                return;
            }
            event.preventDefault()
            this.submittable = true;
            setTimeout(() => {
                this.submittable = false;
            }, 4321)
        }
    }
}

customElements.define('confirm-button', ConfirmButton, {extends: 'button'})