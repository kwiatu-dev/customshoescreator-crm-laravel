<template>
  <button  
    class="btn-action"
    @click="clicked"
  >
    {{ label }}
  </button>
</template>
    
<script setup>
import { router } from '@inertiajs/vue3'
    
const props = defineProps({
  url: {
    required: true,
    type: String,
  },
  label: {
    required: true,
    type: String,
  },
})
    
const clicked = () => {
  router.get(route('restore.state', { url: props.url })) 
}
</script>