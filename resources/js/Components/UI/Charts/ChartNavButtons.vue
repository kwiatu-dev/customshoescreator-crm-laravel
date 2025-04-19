<template>
  <div v-if="labels" class="flex justify-center mb-8 gap-2">
    <button 
      v-for="(data, label) in labels" :key="label"
      class="py-1 px-4 rounded-md shadow-sm text-gray-800 font-medium text-sm bg-white border border-solid border-gray-300 dark:border-gray-700 dark:text-gray-300 transition-all duration-200 ease-in-out dark:bg-gray-600 "
      :class="[{ '': data.active, 'opacity-40': !data.active }]"
      @click="emit('label_click', label)"
    >
      {{ label }}
    </button>
  </div>
  <div 
    v-else 
    class="mb-8" 
    style="height: 30px;"
  />
</template>

<script setup>
defineProps({
  labels: { 
    required: true, 
    type: [Object, null],
  },
})

const emit = defineEmits(['label_click'])
</script>

<style scoped>
  button:hover {
    @apply bg-gray-100 shadow-md transform dark:bg-gray-700;
  }
</style>