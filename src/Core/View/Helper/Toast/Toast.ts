import './Toast.scss';

export default class Toast {
    static displayError(text: string) {
        import('./ToastSingleton').then(t => {
            t.ToastSingleton.instance.display(text, 'error', null)
        })
    }

    static display(text: string, type: string, icon: null|string = null) {
        import('./ToastSingleton').then(t => {
            t.ToastSingleton.instance.display(text, type, icon)
        })
    }
}