<template>
  <div class="flex justify-center space-x-4 mb-4">
    <button 
      v-for="(label, index) in labels" :key="index"
      class="py-2 px-4 rounded-sm shadow-md text-gray-400 font-medium"
      :style="`background: linear-gradient(180deg, ${ colors[index].backgroundColor.replace('{opacity}', '.4') } 0%, ${ colors[index].backgroundColor.replace('{opacity}', '.7') } 50%, ${ colors[index].backgroundColor.replace('{opacity}', '.9') } 100%);`"
      :class="[{ '': label.visible, 'line-through': !label.visible }]"
      @click="emit('label_click', index)"
    >
      {{ label.value }}
    </button>
  </div>
</template>

<script setup>
import { colors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'

defineProps({
  labels: { 
    required: true, 
    type: Object,
  },
})

const emit = defineEmits(['label_click'])
</script>