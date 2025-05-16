<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="relative h-48">
      <img 
        :src="imageUrl" 
        :alt="name"
        @error="handleImageError"
        class="w-full h-full object-cover"
        :class="{ 'opacity-0': !imageLoaded }"
        @load="handleImageLoad"
        crossorigin="anonymous"
        loading="lazy"
      >
      <div 
        v-if="!imageLoaded" 
        class="absolute inset-0 bg-gray-100 flex items-center justify-center"
      >
        <div class="animate-pulse text-center">
          <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span class="text-sm text-gray-500">Loading {{ name }}...</span>
        </div>
      </div>
    </div>
    <div class="p-4">
      <h3 class="text-lg font-semibold text-green-700">{{ name }}</h3>
      <p class="text-sm text-gray-600 mb-2">{{ commonName }}</p>
      <div class="space-y-2">
        <p class="text-sm"><span class="font-medium">Height:</span> {{ height }}</p>
        <p class="text-sm"><span class="font-medium">Salinity tolerance:</span> {{ salinityTolerance }}</p>
        <p class="text-sm"><span class="font-medium">Features:</span> {{ features }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MangroveCard',
  props: {
    name: {
      type: String,
      required: true
    },
    commonName: {
      type: String,
      required: true
    },
    height: {
      type: String,
      required: true
    },
    salinityTolerance: {
      type: String,
      required: true
    },
    features: {
      type: String,
      required: true
    },
    imageUrl: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      imageLoaded: false,
      retryCount: 0,
      maxRetries: 3
    }
  },
  mounted() {
    // Preload the image
    this.preloadImage()
  },
  methods: {
    preloadImage() {
      const img = new Image()
      img.onload = () => {
        this.imageLoaded = true
      }
      img.onerror = (e) => {
        console.error('Image preload failed:', this.imageUrl, e)
        if (this.retryCount < this.maxRetries) {
          this.retryCount++
          setTimeout(() => this.preloadImage(), 1000 * this.retryCount)
        } else {
          this.handleImageError(e)
        }
      }
      img.src = this.imageUrl
    },
    handleImageError(e) {
      console.error('Image failed to load after retries:', this.imageUrl)
      const fallbackUrl = `https://via.placeholder.com/400x300/f0fdf4/166534?text=${encodeURIComponent(this.name)}`
      if (e.target) {
        e.target.src = fallbackUrl
      }
      this.imageLoaded = true // Show the fallback image
    },
    handleImageLoad() {
      this.imageLoaded = true
    }
  }
}
</script> 