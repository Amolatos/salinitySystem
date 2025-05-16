<template>
  <footer class="bg-white shadow-lg mt-6">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Mangrove Summary -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Mangrove Species Overview</h3>
          <div class="space-y-2">
            <p class="text-sm text-gray-600">
              <span class="font-medium">Total Species:</span> 6 common types
            </p>
            <div class="text-sm text-gray-600">
              <span class="font-medium">Height Range:</span>
              <ul class="ml-4 mt-1">
                <li>• Smallest: 1-6m (River Mangrove)</li>
                <li>• Largest: Up to 30m (Orange Mangrove)</li>
              </ul>
            </div>
            <p class="text-sm text-gray-600">
              <span class="font-medium">Key Features:</span> Pneumatophores, prop roots, viviparous seeds
            </p>
          </div>
        </div>

        <!-- System Stats -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Monitoring Statistics</h3>
          <div class="space-y-2">
            <p class="text-sm text-gray-600">
              <span class="font-medium">Last Reading:</span> {{ formatDate(lastReading) }}
            </p>
            <p class="text-sm text-gray-600">
              <span class="font-medium">Total Readings Today:</span> {{ todayReadings }}
            </p>
            <p class="text-sm text-gray-600">
              <span class="font-medium">Average EC Value:</span> {{ averageEC.toFixed(2) }} mS/cm
            </p>
            <p class="text-sm text-gray-600">
              <span class="font-medium">Temperature Range:</span> {{ tempRange.min }}°C - {{ tempRange.max }}°C
            </p>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
          <div class="space-y-2">
            <a href="#" class="block text-sm text-blue-600 hover:text-blue-800">View Historical Data</a>
            <a href="#" class="block text-sm text-blue-600 hover:text-blue-800">Download Reports</a>
            <a href="#" class="block text-sm text-blue-600 hover:text-blue-800">System Documentation</a>
            <a href="#" class="block text-sm text-blue-600 hover:text-blue-800">Mangrove Species Guide</a>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
              Salinity Monitoring System v1.0
              <br>
              © {{ new Date().getFullYear() }} All rights reserved
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
  data() {
    return {
      lastReading: new Date(),
      todayReadings: 24,
      averageEC: 2.75,
      tempRange: {
        min: 24.5,
        max: 32.0
      }
    }
  },
  methods: {
    formatDate(date) {
      return new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      }).format(date)
    }
  }
}
</script> 