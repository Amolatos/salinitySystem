<template>
  <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Salinity Test History</h2>
      <div class="flex space-x-2">
        <button 
          v-for="period in timePeriods" 
          :key="period.value"
          @click="selectedPeriod = period.value"
          class="px-3 py-1 rounded-md text-sm"
          :class="selectedPeriod === period.value ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        >
          {{ period.label }}
        </button>
      </div>
    </div>

    <!-- Chart -->
    <div class="h-[300px] mb-6">
      <canvas ref="chartCanvas"></canvas>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="bg-blue-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-700">Average EC Value</h3>
        <p class="text-2xl font-bold text-blue-800">{{ stats.avgEC.toFixed(2) }} mS/cm</p>
      </div>
      <div class="bg-green-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-green-700">Tests Conducted</h3>
        <p class="text-2xl font-bold text-green-800">{{ stats.totalTests }}</p>
      </div>
      <div class="bg-red-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-red-700">Temperature Range</h3>
        <p class="text-2xl font-bold text-red-800">{{ stats.minTemp }}°C - {{ stats.maxTemp }}°C</p>
      </div>
      <div class="bg-purple-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-purple-700">Latest Reading</h3>
        <p class="text-2xl font-bold text-purple-800">{{ stats.latestEC.toFixed(2) }} mS/cm</p>
      </div>
    </div>

    <!-- Recent Tests Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">EC Value</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temperature</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="reading in recentReadings" :key="reading.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ formatDate(reading.timestamp) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ reading.ec_value.toFixed(2) }} mS/cm
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ reading.temperature.toFixed(1) }}°C
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ formatCoordinate(reading.latitude, 'lat') }}, 
              {{ formatCoordinate(reading.longitude, 'long') }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'

export default {
  name: 'TestHistory',
  setup() {
    const chartCanvas = ref(null)
    const chart = ref(null)
    const selectedPeriod = ref('24h')
    const recentReadings = ref([])
    const stats = ref({
      avgEC: 0,
      totalTests: 0,
      minTemp: 0,
      maxTemp: 0,
      latestEC: 0
    })

    const timePeriods = [
      { label: '24h', value: '24h' },
      { label: '7d', value: '7d' },
      { label: '30d', value: '30d' },
      { label: 'All', value: 'all' }
    ]

    const fetchReadings = async () => {
      try {
        const response = await fetch(`/api/readings?period=${selectedPeriod.value}`)
        const data = await response.json()
        recentReadings.value = data.readings
        updateStats(data.readings)
        updateChart(data.readings)
      } catch (error) {
        console.error('Error fetching readings:', error)
      }
    }

    const updateStats = (readings) => {
      if (readings.length === 0) return

      stats.value = {
        avgEC: readings.reduce((sum, r) => sum + r.ec_value, 0) / readings.length,
        totalTests: readings.length,
        minTemp: Math.min(...readings.map(r => r.temperature)),
        maxTemp: Math.max(...readings.map(r => r.temperature)),
        latestEC: readings[0].ec_value
      }
    }

    const updateChart = (readings) => {
      if (!chartCanvas.value || readings.length === 0) return

      const ctx = chartCanvas.value.getContext('2d')
      
      if (chart.value) {
        chart.value.destroy()
      }

      chart.value = new Chart(ctx, {
        type: 'line',
        data: {
          labels: readings.map(r => formatDate(r.timestamp)),
          datasets: [
            {
              label: 'EC Value (mS/cm)',
              data: readings.map(r => r.ec_value),
              borderColor: 'rgb(34, 197, 94)',
              tension: 0.1
            },
            {
              label: 'Temperature (°C)',
              data: readings.map(r => r.temperature),
              borderColor: 'rgb(239, 68, 68)',
              tension: 0.1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top'
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      })
    }

    const formatDate = (timestamp) => {
      return new Date(timestamp).toLocaleString()
    }

    const formatCoordinate = (value, type) => {
      const direction = type === 'lat' 
        ? (value >= 0 ? 'N' : 'S')
        : (value >= 0 ? 'E' : 'W')
      return `${Math.abs(value).toFixed(4)}° ${direction}`
    }

    watch(selectedPeriod, () => {
      fetchReadings()
    })

    onMounted(() => {
      fetchReadings()
    })

    return {
      chartCanvas,
      selectedPeriod,
      timePeriods,
      recentReadings,
      stats,
      formatDate,
      formatCoordinate
    }
  }
}
</script> 