class ReactiveDetails extends HTMLDetailsElement {
    replaceWith(...nodes) {
        nodes.forEach(node => {
            if (node instanceof HTMLDetailsElement) {
                node.open = this.open;
            }
        })
        super.replaceWith(...nodes);
    }
}
customElements.define('reactive-details', ReactiveDetails, {extends: 'details'})