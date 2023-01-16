class Analytics extends HTMLScriptElement {
    private data: {
        rid: string,
        rt: number,
        events: {
            hidden: string
        },
        params: {
            rid: string,
            rt: number,
            click: string,
            event: string
        }
    } = JSON.parse(this.textContent ?? '{}')
    private lastLink = '';
    private readonly clickHandler: (event: MouseEvent) => void;
    private readonly visibilityChangeHandler: (event: Event) => void;

    constructor() {
        super();
        this.clickHandler = (event: MouseEvent) => {
            const link = (event.target as HTMLElement).closest('a')
            if (link && link.hasAttribute('href')) {
                this.lastLink = link.getAttribute('href') ?? ''
            }
        };
        this.visibilityChangeHandler = (event: Event) => {
            if (document.visibilityState === 'hidden') {
                const uri = new URL(location.href, document.baseURI)
                uri.searchParams.set(this.data.params.rid, this.data.rid)
                uri.searchParams.set(this.data.params.rt + '', this.data.rt + '')
                uri.searchParams.set(this.data.params.event, this.data.events.hidden)
                uri.searchParams.set(this.data.params.click, this.lastLink)
                fetch(uri)
            }
        }
    }

    public connectedCallback() {
        document.addEventListener('visibilitychange', this.visibilityChangeHandler)
        document.addEventListener('click', this.clickHandler)
    }

    public disconnectedCallback() {
        document.removeEventListener('visibilitychange', this.visibilityChangeHandler)
        document.removeEventListener('click', this.clickHandler)

    }
}

customElements.define('analytics-data', Analytics, {extends: 'script'})