
class ReactiveForm extends HTMLFormElement {
    constructor() {
        super();
        this.onsubmit = this.handleSubmit
    }

    private async handleSubmit(event: SubmitEvent) {
        const submitter = event.submitter as HTMLButtonElement;
        submitter.disabled = true;

        event.preventDefault()
        const formData = new FormData(this);

        this.querySelectorAll("[contenteditable][name]").forEach(elem => {
            const name = elem.getAttribute('name') as string;
            formData.set(name, elem.innerHTML);
        });

        const closestElementWithId = this.closest('[id]');

        const response = await fetch(this.findFormActionFromSubmitter(submitter), {
            method: this.method,
            body: formData,
            redirect: closestElementWithId ? 'follow' : 'manual'
        })

        if (!closestElementWithId || (closestElementWithId.getAttribute('id')?.trim() + '') === '') {
            location.reload();
            return;
        }
        const id = closestElementWithId.getAttribute('id') as string;

        const parser = new DOMParser();
        const document = parser.parseFromString(await response.text(), 'text/html');
        const messages = document.querySelector('[is=toast-messages]');
        const validations = document.querySelector('[is=toast-validations]');

        if (messages) {
            window.document.body.append(messages)
        }

        if (validations) {
            window.document.body.append(validations)
        }

        if (response.ok && !validations) {
            const replacement = document.getElementById(id);
            if (replacement) {
                closestElementWithId.replaceWith(replacement)
            } else {
                closestElementWithId.remove()
            }
        } else {
            submitter.disabled = false;
        }
    }


    private findFormActionFromSubmitter(submitter: HTMLButtonElement): string {
        if (submitter.hasAttribute('formaction')) {
            return submitter.formAction;
        }
        return this.action;
    }
}

customElements.define('reactive-form', ReactiveForm, {extends: 'form'})