export default class ToastMessage {
    private readonly _element: HTMLElement;
    private timer;

    constructor(
        private icon: string,
        private type: string,
        private text: string
    ) {
        this._element = document.createElement('div')
        this._element.classList.add('toast__message');
        this._element.classList.add('fade');
        this._element.classList.add(type);
        this._element.innerHTML = `
<svg>
    <use href="/icons.svg#${icon}"/>
</svg>
<p>
    ${text}
</p>
`
        this._element.addEventListener('animationend', () => {
            if (this._element.classList.contains('fade')) {
                this._element.remove()
            }
        })
    }

    get element(): HTMLElement {
        return this._element;
    }
}
