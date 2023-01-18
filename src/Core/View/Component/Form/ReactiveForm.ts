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

        if (response.ok) {
            const parser = new DOMParser();
            const document = parser.parseFromString(await response.text(), 'text/html');
            const replacement = document.getElementById(id);
            if (replacement) {
                closestElementWithId.replaceWith(replacement)
            } else {
                closestElementWithId.remove()
            }
        } else {
            import('../Toast/Toast').then(t => {
                t.default.instance.display(response.headers.get('status') ?? response.statusText)
            })
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