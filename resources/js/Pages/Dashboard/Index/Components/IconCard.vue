<template>
  <div class=" card !bg-gray-200 dark:!bg-gray-700">
    <h2 class="dark:!text-gray-400"><slot /></h2>
    <div class="flex flex-row flex-nowrap items-center gap-4">
      <font-awesome-icon :icon="icon" style="font-size: 40px;" class="text-indigo-500 dark:text-indigo-400" />
      <div class="text-gray-800 font-medium text-3xl dark:text-gray-300">{{ value }}</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  icon: {
    type: Array,
    required: true,
  },
  value: {
    type: String,
    required: true,
  },
})
</script>