<template>
  <div class="flex justify-center mb-8 gap-2">
    <button 
      v-for="(label, index) in labels" :key="index"
      class="py-1 px-4 rounded-md shadow-sm text-gray-800 font-medium text-sm bg-white border border-solid border-gray-300 dark:border-gray-700 dark:text-gray-300 transition-all duration-200 ease-in-out dark:bg-gray-600 "
      :class="[{ '': label.visible, 'opacity-40': !label.visible }]"
      @click="emit('label_click', index)"
    >
      {{ label.value }}
    </button>
  </div>
</template>

<script setup>
defineProps({
  labels: { 
    required: true, 
    type: Object,
  },
})

const emit = defineEmits(['label_click'])
</script>

<style scoped>
button:hover {
  @apply bg-gray-100 shadow-md transform dark:bg-gray-700;
}
</style>