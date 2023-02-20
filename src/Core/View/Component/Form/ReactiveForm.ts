import './Form.scss'
import ScriptLoader from "../../Helper/ScriptLoader";
import StyleLoader from "../../Helper/StyleLoader";

class ReactiveForm extends HTMLFormElement {
    public context: null | string = null;

    constructor() {
        super();
        this.onsubmit = this.handleSubmit
        let context = this.getAttribute('context')
            ?? this.closest('[id]')?.getAttribute('id')
            ?? null;
        if (context && window.document.getElementById(context)) {
            this.context = context;
        }
    }

    private async handleSubmit(event: SubmitEvent) {
        const submitter = event.submitter as HTMLButtonElement;
        submitter.disabled = true;

        event.preventDefault()
        const formData = new FormData(this);

        const response = await fetch(this.findFormActionFromSubmitter(submitter), {
            method: this.method,
            body: formData,
        })

        if (null === this.context) {
            location.assign(response.url);
            return;
        }

        const parser = new DOMParser();
        const document = parser.parseFromString(await response.text(), 'text/html');
        const messages = document.querySelector('[is=toast-messages]');
        const validations = document.querySelector('[is=toast-validations]');

        ScriptLoader.loadFrom(document)
        StyleLoader.loadFrom(document)

        if (messages) {
            window.document.body.append(messages)
        }

        if (validations) {
            window.document.body.append(validations)
        }

        if (response.ok && !validations) {
            const replacement = document.getElementById(this.context);
            if (replacement) {
                window.document.getElementById(this.context)?.replaceWith(replacement)
            } else {
                window.document.getElementById(this.context)?.remove()
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