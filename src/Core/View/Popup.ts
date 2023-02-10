import "winbox";

export default class Popup extends window.WinBox {
    constructor(params) {
        super(params);
    }
    public static open(url: string): void {
        if (matchMedia('only screen and (max-width: 720px)').matches) {
            window.open(url, '_blank', 'popup')
            return;
        }
        const iconURL = new URL(url);
        iconURL.pathname = '/favicon.ico'
        const popup = new Popup({
            url: url,
            x: 'center',
            y: 'center',
            height: '100%',
            class: 'no-full',
            icon: iconURL.toString()
        });
        const iframe = popup.body.querySelector('iframe') as HTMLIFrameElement;
        iframe.addEventListener('load', () => {
            if (iframe.contentDocument?.title) {
                popup.setTitle(iframe.contentDocument?.title)
            }
            iframe.contentWindow?.postMessage({remove: 'header'}, '*');
            iframe.contentWindow?.postMessage({remove: 'footer'}, '*');
        })
    }
}
