export default class AttributeStorage {
    constructor(private element: HTMLElement, private attributes: string[]) {
        this.restoreAttributes();
    }

    restoreAttributes() {
        if (this.element.id) {
            for (const attr of this.attributes) {
                const value = sessionStorage.getItem(this.key(attr));
                if (null === value) {
                    this.element.removeAttribute(attr)
                } else {
                    this.element.setAttribute(attr, value)
                }
            }
        }
    }

    key(attr: string): string {
        return this.element.className + this.element.id + attr;
    }

    persistAttribute(attr: string, value: null|string)
    {
        if (this.element.id) {
            if (null === value) {
                sessionStorage.removeItem(this.key(attr))
            } else {
                sessionStorage.setItem(this.key(attr), value)
            }
        }
    }
}