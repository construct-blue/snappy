import Popup from "./Popup";


window.addEventListener('message', e => {
    if (e.data.popup) {
        Popup.open(e.data.popup)
    }
    if (e.data.remove) {
        document.querySelector(e.data.remove)?.remove()
    }
})

window.addEventListener('click', event => {
    const anchor = (event.target as HTMLElement).closest('a');
    if (!anchor?.href) {
        return;
    }
    if (window.top && window.self !== window.top) {
        event.preventDefault();
        window.top.postMessage({popup: anchor.href}, '*');
    } else if (anchor?.target === 'popup') {
        event.preventDefault()
        Popup.open(anchor.href)
    }
})