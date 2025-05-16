import './bootstrap';
import { createApp } from 'vue'
import axios from 'axios'

// Import components
import SalinityMonitor from './components/SalinityMonitor.vue'
import MapComponent from './components/MapComponent.vue'
import MangroveTypes from './components/MangroveTypes.vue'
import TestHistory from './components/TestHistory.vue'

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

// Create Vue app
const app = createApp({})

// Register components globally
app.component('salinity-monitor', SalinityMonitor)
app.component('map-component', MapComponent)
app.component('mangrove-types', MangroveTypes)
app.component('test-history', TestHistory)

// Configure Vue app
app.config.globalProperties.$axios = axios
app.config.compilerOptions.delimiters = ['${', '}'] // Avoid conflict with Blade

// Mount the app
app.mount('#app')
