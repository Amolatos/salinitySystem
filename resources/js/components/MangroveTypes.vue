<template>
  <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h2 class="text-2xl font-bold mb-6">Common Mangrove Species</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <MangroveCard
        v-for="mangrove in mangroves"
        :key="mangrove.name"
        v-bind="mangrove"
      />
    </div>
  </div>
</template>

<script>
import MangroveCard from './MangroveCard.vue'

export default {
  name: 'MangroveTypes',
  components: {
    MangroveCard
  },
  data() {
    return {
      mangroves: [
        {
          name: 'Avicennia marina',
          commonName: 'Grey Mangrove',
          height: '3-10 meters',
          salinityTolerance: 'High (up to 85 PSU)',
          features: 'Pneumatophores, salt-excreting leaves',
          imageUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Avicennia_marina_%28grey_mangrove%29.JPG/800px-Avicennia_marina_%28grey_mangrove%29.JPG'
        },
        {
          name: 'Rhizophora mucronata',
          commonName: 'Red Mangrove',
          height: '20-25 meters',
          salinityTolerance: 'Moderate to High',
          features: 'Distinctive prop roots, viviparous seeds',
          imageUrl: 'https://imgs.search.brave.com/WUBnRBa7UrAdoubmUdwfYMjIlh_tUX7LrMOsHrZ52Nw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy9m/L2YzL1JoaXpvcGhv/cmFfbWFuZ2xlLmpw/Zw'
        },
        {
          name: 'Sonneratia alba',
          commonName: 'White Mangrove',
          height: '15-20 meters',
          salinityTolerance: 'High',
          features: 'Pneumatophores, cork-like fruits',
          imageUrl: 'https://imgs.search.brave.com/tLbdz2Hpm2QTRQAeZWnkiJANXbex0r4XQ1gDfqiTpP4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy84/LzhjL1Nvbm5lcmF0/aWFfYWxiYV8tX2Zy/dWl0Xyg4MzQ5OTgw/MjY0KS5qcGc'
        },
        {
          name: 'Bruguiera gymnorrhiza',
          commonName: 'Large-leafed Orange Mangrove',
          height: 'Up to 30 meters',
          salinityTolerance: 'Moderate',
          features: 'Knee roots, viviparous propagules',
          imageUrl: 'https://imgs.search.brave.com/-zjxg1tCXWCTZGYJ22mjn86L2x7T35PD0HwGSIs9uUc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy83/LzcyL0JydWd1aWVy/YV9neW1ub3JyaGl6/YS5qcGc'
        },
        {
          name: 'Ceriops tagal',
          commonName: 'Yellow Mangrove',
          height: '6-15 meters',
          salinityTolerance: 'High',
          features: 'Buttress roots, club-shaped propagules',
          imageUrl: 'https://imgs.search.brave.com/WCU2ucKbvPa3AiO_nWYxW3pCzyr5yfhrvFGoia8dF-4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy83/LzcyL0NlcmlvcHNf/dGFnYWxfLV9mbG93/ZXJzXyg4MzQ5OTgw/MjUwKS5qcGc'
        },
        {
          name: 'Aegiceras corniculatum',
          commonName: 'River Mangrove',
          height: '1-6 meters',
          salinityTolerance: 'Moderate',
          features: 'Small curved propagules, clustered flowers',
          imageUrl: 'https://imgs.search.brave.com/tbtjfXt57juyYS8-HLN87kpgtuEbwEeSkee3tv-HEk0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9ibG9n/Z2VyLmdvb2dsZXVz/ZXJjb250ZW50LmNv/bS9pbWcvYi9SMjl2/WjJ4bC9BVnZYc0Vp/WlQ4RHJLSEtIWF82/Umloc0ozSXRJVm9T/NFdNckpXQmxDcEVr/YTBaR1lqWnpxXzVj/NzkzbDJRdm9FbE00/NmRuOUR4Wmw4MlJs/bjBUQUR5UktrVWRS/bF84RFkzOE5YcTZk/RUJYc2NjcU1oSTFx/LXBsUkhwMUxSSXJt/cmxyeE9xaHdJTnlo/Z3NRL3M0MDAvQWVn/aWNlcmFzLWNvcm5p/Y3VsYXR1bS5qcGc'
        }
      ]
    }
  }
}
</script> 