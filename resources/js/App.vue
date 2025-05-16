<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-green-600">Salinity Monitoring System</h1>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="toggleTesting" 
              class="px-4 py-2 rounded-md text-sm font-medium"
              :class="[
                isProcessing ? 'bg-yellow-500 text-white hover:bg-yellow-600' :
                isActiveTesting ? 'bg-red-500 text-white hover:bg-red-600' : 
                'bg-green-500 text-white hover:bg-green-600'
              ]"
              :disabled="isProcessing"
            >
              {{ 
                isProcessing ? `Processing (${processingCountdown}s)` :
                isActiveTesting ? 'Stop Testing' : 'Start Testing' 
              }}
            </button>
            <span class="text-sm text-gray-500">Last Updated: {{ formatDate(lastUpdate) }}</span>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
          <!-- Processing Message -->
          <div v-if="isProcessing" class="bg-yellow-50 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-center space-x-4">
              <svg class="animate-spin h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="text-lg font-medium text-yellow-700">
                Initializing Sensor... ({{ processingCountdown }}s)
              </span>
            </div>
          </div>

          <!-- Salinity Data Section -->
          <div v-if="isActiveTesting && !isProcessing" class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Current Readings</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <!-- Current EC Value -->
              <div class="bg-blue-50 rounded-lg p-4 transform hover:scale-105 transition-transform">
                <h3 class="text-lg font-semibold text-blue-700">Current EC Value</h3>
                <p class="text-3xl font-bold">{{ currentEcValue }} mS/cm</p>
                <p class="text-sm text-blue-600 mt-2">Electrical Conductivity</p>
              </div>
              
              <!-- Temperature -->
              <div class="bg-red-50 rounded-lg p-4 transform hover:scale-105 transition-transform">
                <h3 class="text-lg font-semibold text-red-700">Temperature</h3>
                <p class="text-3xl font-bold">{{ temperature }}°C</p>
                <p class="text-sm text-red-600 mt-2">Water Temperature</p>
              </div>

              <!-- GPS Location -->
              <div class="bg-green-50 rounded-lg p-4 transform hover:scale-105 transition-transform">
                <h3 class="text-lg font-semibold text-green-700">GPS Location</h3>
                <div class="space-y-1">
                  <p class="text-sm text-green-600">Latitude: {{ formatCoordinate(latitude, 'lat') }}</p>
                  <p class="text-sm text-green-600">Longitude: {{ formatCoordinate(longitude, 'long') }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Test History -->
          <TestHistory v-if="isActiveTesting" />

          <!-- Map Component -->
          <MapComponent 
            :latitude="latitude"
            :longitude="longitude"
            :is-active="true"
          />

          <!-- Mangrove Types Section -->
          <MangroveTypes />

          <!-- Footer -->
          <Footer />
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import MangroveTypes from './components/MangroveTypes.vue'
import TestHistory from './components/TestHistory.vue'
import MapComponent from './components/MapComponent.vue'
import Footer from './components/Footer.vue'

export default {
  name: 'App',
  components: {
    MangroveTypes,
    TestHistory,
    MapComponent,
    Footer
  },
  data() {
    return {
      isActiveTesting: false,
      isProcessing: false,
      processingCountdown: 5, // 5 seconds countdown
      currentEcValue: 2.5,
      temperature: 28.5,
      latitude: 14.5995,
      longitude: 120.9842,
      lastUpdate: new Date(),
      dataFetchInterval: null,
      processingInterval: null
    }
  },
  methods: {
    toggleTesting() {
      if (this.isActiveTesting) {
        // Stop testing
        this.isActiveTesting = false
        this.stopDataFetching()
      } else {
        // Start testing with processing state
        this.startProcessing()
      }
    },
    startProcessing() {
      this.isProcessing = true
      this.processingCountdown = 5 // Reset countdown
      
      // Start countdown
      this.processingInterval = setInterval(() => {
        this.processingCountdown--
        if (this.processingCountdown <= 0) {
          clearInterval(this.processingInterval)
          this.isProcessing = false
          this.isActiveTesting = true
          this.startDataFetching()
        }
      }, 1000)
    },
    startDataFetching() {
      this.fetchSensorData() // Fetch immediately
      this.dataFetchInterval = setInterval(this.fetchSensorData, 5000) // Then every 5 seconds
    },
    stopDataFetching() {
      if (this.dataFetchInterval) {
        clearInterval(this.dataFetchInterval)
        this.dataFetchInterval = null
      }
      if (this.processingInterval) {
        clearInterval(this.processingInterval)
        this.processingInterval = null
      }
      this.isProcessing = false
    },
    formatDate(date) {
      return new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: true
      }).format(date)
    },
    formatCoordinate(value, type) {
      const direction = type === 'lat' 
        ? (value >= 0 ? 'N' : 'S')
        : (value >= 0 ? 'E' : 'W')
      return `${Math.abs(value).toFixed(4)}° ${direction}`
    },
    async fetchSensorData() {
      try {
        const response = await fetch('/api/readings')
        const data = await response.json()
        if (data.readings && data.readings.length > 0) {
          const latest = data.readings[0]
          this.currentEcValue = latest.ec_value
          this.temperature = latest.temperature
          this.latitude = latest.latitude
          this.longitude = latest.longitude
          this.lastUpdate = new Date(latest.timestamp)
        }
      } catch (error) {
        console.error('Error fetching sensor data:', error)
      }
    }
  },
  beforeUnmount() {
    this.stopDataFetching() // Clean up intervals when component is destroyed
  }
}
</script>

<style>
/* Add any custom styles here */
</style> 