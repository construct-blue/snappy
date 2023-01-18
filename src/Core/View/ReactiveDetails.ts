import AttributeStorage from "./AttributeStorage";

class ReactiveDetails extends HTMLDetailsElement {
    private attributeStorage = new AttributeStorage(this, ReactiveDetails.observedAttributes)

    static get observedAttributes(): string[] {
        return ['open'];
    }

    attributeChangedCallback(name, oldValue, newValue): void {
        this.attributeStorage.persistAttribute(name, newValue)
    }
}

customElements.define('reactive-details', ReactiveDetails, {extends: 'details'})