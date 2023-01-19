import "winbox";

export default class ViewWindow extends window.WinBox {
    static windows: ViewWindow[] = [];
    history: string[] = [];
    historyIndex = 0;

    iframe: HTMLIFrameElement
    constructor(params) {
        super(params);
        ViewWindow.windows.push(this);
        this.history.push(params.url);
        const iframe = this.body.firstElementChild as HTMLIFrameElement;
        this.iframe = iframe;
        iframe.addEventListener('load', () => {
            iframe.contentWindow?.postMessage({
                id: this.id,
                command: 'getTitle'
            }, {targetOrigin: '*'})
        })
        // @ts-ignore
        this.removeControl('wb-full');
        // @ts-ignore
        this.addControl({
            index: 0,
            class: "wb-forward",
            image: require("./arrow-right.svg"),
            click: function (event, winbox) {
                winbox.forward()
            }
        });
        // @ts-ignore
        this.addControl({
            index: 0,
            class: "wb-back",
            image: require("./arrow-left.svg"),
            click: function (event, winbox) {
                winbox.back()
            }
        });
    }

    static getWindow(id: string): null | ViewWindow {
        for (const window of ViewWindow.windows) {
            if (window.id == id) {
                return window;
            }
        }
        return null;
    }

    static openUrl(url: string, title = '') {
        const iconUrl = new URL(url);
        iconUrl.pathname = '/favicon.ico'
        return new ViewWindow({
            title: title,
            x: 'center',
            y: 'center',
            icon: iconUrl.toString(),
            background: "#252b4e",
            url: url
        });
    }

    static openAnchor(anchor: HTMLAnchorElement) {
        ViewWindow.openUrl(anchor.href, anchor.innerText);
    }

    back() {
        if (this.history.at(this.historyIndex - 1)) {
            this.setUrl(this.history.at(--this.historyIndex) + '')
        }
    }

    forward() {
        if (this.history.at(this.historyIndex + 1)) {
            this.setUrl(this.history.at(++this.historyIndex) + '')
        }
    }

    navigate(src) {
        this.setUrl(src);
        this.history.push(src)
        this.historyIndex++;
    }
}


if (window !== window.parent) {
    window.parent.postMessage({title: document.title, url: window.location.href}, '*')
}

window.addEventListener('message', e => {
    const data = e.data;
    if (data.command == 'getTitle') {
        window['id'] = data.id
        data.title = document.title;
        data.command = 'setTitle'
        e.source?.postMessage(data, {targetOrigin: '*'})
    }
    if (data.command == 'setTitle') {
        ViewWindow.getWindow(data.id)?.setTitle(data.title)
    }
    if (data.command == 'back') {
        ViewWindow.getWindow(data.id)?.back()
    }
    if (data.command == 'forward') {
        ViewWindow.getWindow(data.id)?.forward()
    }
    if (data.command == 'navigate') {
        ViewWindow.getWindow(data.id)?.navigate(data.src)
    }
})

if (window !== window.parent) {
    document.addEventListener('click', event => {
        const anchor = (event.target as HTMLElement).closest('a');
        if (anchor?.href) {
            event.preventDefault()
            window.parent.postMessage({
                    id: window['id'],
                    command: 'navigate',
                    src: anchor.href
                },
                {targetOrigin: '*'}
            )
        }
    })
}

