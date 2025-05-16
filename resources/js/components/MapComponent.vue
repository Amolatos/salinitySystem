<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Sensor Location</h2>
      
      <!-- Search Box -->
      <div class="w-full md:w-1/3">
        <div class="relative">
          <input 
            type="text" 
            v-model="searchQuery"
            @keyup.enter="searchLocation"
            placeholder="Search location..."
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          >
          <button 
            @click="searchLocation"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-500"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Map Container -->
    <div class="w-full h-[400px] md:h-[500px] rounded-lg overflow-hidden" ref="mapContainer"></div>

    <!-- Location Info -->
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="bg-gray-50 rounded-lg p-4">
        <div class="text-sm text-gray-500">Current Location</div>
        <div class="text-lg font-semibold text-gray-800">
          {{ latitude.toFixed(6) }}, {{ longitude.toFixed(6) }}
        </div>
      </div>
      <div class="bg-gray-50 rounded-lg p-4">
        <div class="text-sm text-gray-500">Status</div>
        <div class="text-lg font-semibold" :class="isActive ? 'text-green-600' : 'text-red-600'">
          {{ isActive ? 'Active' : 'Inactive' }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

export default {
  name: 'MapComponent',
  props: {
    latitude: {
      type: Number,
      required: true
    },
    longitude: {
      type: Number,
      required: true
    },
    isActive: {
      type: Boolean,
      default: true
    }
  },
  setup(props) {
    const mapContainer = ref(null)
    let map = null
    let marker = null
    let searchMarker = null
    const searchQuery = ref('')

    const initializeMap = () => {
      if (!mapContainer.value || map) return

      // Fix Leaflet default icon path issues
      delete L.Icon.Default.prototype._getIconUrl
      L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png'
      })

      // Create map instance
      map = L.map(mapContainer.value, {
        center: [props.latitude, props.longitude],
        zoom: 13,
        zoomControl: true,
        scrollWheelZoom: true
      })

      // Add OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
      }).addTo(map)

      // Create custom icon for sensor
      const sensorIcon = L.icon({
        iconUrl: '/images/sensor-marker.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
      })

      // Add sensor marker
      marker = L.marker([props.latitude, props.longitude], { icon: sensorIcon })
        .bindPopup('Salinity Sensor')
        .addTo(map)

      // Force a map refresh
      setTimeout(() => {
        map.invalidateSize()
      }, 100)
    }

    const updateMarkerPosition = () => {
      if (!map || !marker) return

      const position = [props.latitude, props.longitude]
      marker.setLatLng(position)
      map.setView(position, map.getZoom())
    }

    const searchLocation = async () => {
      if (!searchQuery.value) return

      try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery.value)}`)
        const data = await response.json()

        if (data && data.length > 0) {
          const location = data[0]
          const position = [parseFloat(location.lat), parseFloat(location.lon)]

          // Remove previous search marker if exists
          if (searchMarker) {
            map.removeLayer(searchMarker)
          }

          // Add new search marker
          searchMarker = L.marker(position, {
            icon: L.icon({
              iconUrl: '/images/search-marker.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34]
            })
          })
            .bindPopup(location.display_name)
            .addTo(map)

          // Fit bounds to show both markers
          const bounds = L.latLngBounds([position, [props.latitude, props.longitude]])
          map.fitBounds(bounds, { padding: [50, 50] })
        }
      } catch (error) {
        console.error('Error searching location:', error)
      }
    }

    // Watch for prop changes
    watch(() => [props.latitude, props.longitude], updateMarkerPosition)

    onMounted(() => {
      initializeMap()
    })

    onUnmounted(() => {
      if (map) {
        map.remove()
      }
    })

    return {
      mapContainer,
      searchQuery,
      searchLocation
    }
  }
}
</script>

<style>
@import 'leaflet/dist/leaflet.css';

/* Fix Leaflet marker icon paths */
.leaflet-default-icon-path {
  background-image: url('https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png');
}

/* Ensure map container has proper dimensions */
#map {
  min-height: 400px;
  width: 100%;
  z-index: 1;
}

/* Style the map controls */
.leaflet-control-zoom {
  border: none !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.leaflet-control-zoom a {
  background-color: white !important;
  color: #333 !important;
}

/* Style the search results dropdown */
.search-results {
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
}

.search-result-item {
  transition: background-color 0.2s;
}

.search-result-item:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

/* Ensure popups are styled properly */
.leaflet-popup-content {
  margin: 8px 12px;
  text-align: center;
}

.leaflet-popup-content-wrapper {
  border-radius: 8px;
}
</style> 