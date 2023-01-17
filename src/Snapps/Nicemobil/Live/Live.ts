import './Live.scss'
import 'leaflet/dist/leaflet.css'
import { Icon, LatLngBounds, map, Map, marker, tileLayer } from 'leaflet'

Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
})

class Live extends HTMLElement {
  private interval: NodeJS.Timeout
  private static zoom = 10
  private static position: null|LatLngBounds;
  private map: Map

  connectedCallback () {
    this.interval = setInterval(this.refresh.bind(this), 60000)

    if (this.querySelector('#map')) {
      const lat = parseFloat('' + this.dataset.lat)
      const lng = parseFloat('' + this.dataset.lng)

      this.map = map('map');
      if (Live.position) {
        this.map.setView(Live.position.getCenter(), Live.zoom)
      } else {
        this.map.setView({ lat: lat, lng: lng }, Live.zoom)
      }
      this.map.on('zoom', () => {
        Live.zoom = this.map.getZoom()
      })
      this.map.on('move', () => {
        Live.position = this.map.getBounds();
      })
      this.map.attributionControl.setPrefix('')

      tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 15, }).addTo(this.map)

      marker([lat, lng]).addTo(this.map)
    }
  }

  refresh () {

  }

  disconnectedCallback () {
    clearInterval(this.interval)
  }
}

customElements.define('nicemobil-live', Live, {extends: 'main'})