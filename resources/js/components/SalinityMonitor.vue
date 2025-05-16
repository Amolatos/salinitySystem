<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Real-time Salinity Monitoring</h2>
    
    <!-- Current Readings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
        <div class="text-sm text-gray-500">EC Value</div>
        <div class="text-2xl font-bold text-gray-800">{{ currentReading.ec_value || 0 }} mS/cm</div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
        <div class="text-sm text-gray-500">Temperature</div>
        <div class="text-2xl font-bold text-gray-800">{{ currentReading.temperature || 0 }}°C</div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
        <div class="text-sm text-gray-500">Location</div>
        <div class="text-lg font-semibold text-gray-800">
          {{ currentReading.latitude || 0 }}, {{ currentReading.longitude || 0 }}
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
        <div class="text-sm text-gray-500">Last Updated</div>
        <div class="text-lg font-semibold text-gray-800">
          {{ formatTimestamp(currentReading.timestamp) }}
        </div>
      </div>
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-blue-50 rounded-lg p-4">
        <div class="text-sm text-blue-600">Today's Readings</div>
        <div class="text-xl font-bold text-blue-800">{{ stats.todayReadings || 0 }}</div>
      </div>
      
      <div class="bg-green-50 rounded-lg p-4">
        <div class="text-sm text-green-600">Average EC</div>
        <div class="text-xl font-bold text-green-800">{{ stats.averageEC?.toFixed(2) || 0 }} mS/cm</div>
      </div>
      
      <div class="bg-yellow-50 rounded-lg p-4">
        <div class="text-sm text-yellow-600">Temperature Range</div>
        <div class="text-xl font-bold text-yellow-800">
          {{ stats.tempRange?.min || 0 }}°C - {{ stats.tempRange?.max || 0 }}°C
        </div>
      </div>
    </div>

    <!-- Chart -->
    <div class="h-64 w-full">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import Chart from 'chart.js/auto'

export default {
  name: 'SalinityMonitor',
  
  setup() {
    const currentReading = ref({
      ec_value: 0,
      temperature: 0,
      latitude: 0,
      longitude: 0,
      timestamp: null
    })
    
    const stats = ref({
      todayReadings: 0,
      averageEC: 0,
      tempRange: { min: 0, max: 0 }
    })
    
    const chartCanvas = ref(null)
    let chart = null
    let updateInterval = null

    const fetchData = async () => {
      try {
        const response = await fetch('/api/readings')
        const data = await response.json()
        currentReading.value = data.current || {}
        stats.value = data.stats || {}
        updateChart()
      } catch (error) {
        console.error('Error fetching data:', error)
      }
    }

    const formatTimestamp = (timestamp) => {
      if (!timestamp) return '-'
      return new Date(timestamp).toLocaleString()
    }

    const updateChart = () => {
      if (!chart || !currentReading.value) return

      const labels = chart.data.labels
      const data = chart.data.datasets[0].data

      // Add new data point
      labels.push(formatTimestamp(currentReading.value.timestamp))
      data.push(currentReading.value.ec_value)

      // Keep only last 10 readings
      if (labels.length > 10) {
        labels.shift()
        data.shift()
      }

      chart.update()
    }

    onMounted(() => {
      // Initialize chart
      const ctx = chartCanvas.value.getContext('2d')
      chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [],
          datasets: [{
            label: 'EC Value (mS/cm)',
            data: [],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
            fill: false
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'EC Value (mS/cm)'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Time'
              }
            }
          }
        }
      })

      // Fetch initial data
      fetchData()

      // Set up auto-update interval
      updateInterval = setInterval(fetchData, 5000) // Update every 5 seconds
    })

    onUnmounted(() => {
      if (updateInterval) {
        clearInterval(updateInterval)
      }
      if (chart) {
        chart.destroy()
      }
    })

    return {
      currentReading,
      stats,
      chartCanvas,
      formatTimestamp
    }
  }
}
</script>

<style scoped>
.reading-grid {
  @apply grid gap-4;
}

@screen sm {
  .reading-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@screen lg {
  .reading-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
</style> 