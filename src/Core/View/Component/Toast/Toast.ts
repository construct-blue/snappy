import './Toast.scss'

export default class Toast extends HTMLElement {
  private static _instance: Toast

  public constructor () {
    super()
    this.classList.add('toast')
    document.body.append(this)
  }

  public display (message: string) {
    const element = document.createElement('div')
    element.classList.add('toast__message')
    element.innerHTML = `
<p class="toast__head">
    <svg>
        <use href="/icons.svg#alert-circle"/>
    </svg>
    Error
</p>
<p class="toast__text">
    ${message}
</p>
`
    element.addEventListener('transitionend', () => {
      if (element.classList.contains('fade')) {
        element.remove()
      }
    })

    setTimeout(() => {
      element.classList.add('fade')
    }, 5000)

    this.append(element)
  }

  static get instance (): Toast {
    if (undefined === this._instance) {
      this._instance = new Toast()
    }
    return this._instance
  }
}

customElements.define('core-toast', Toast)

