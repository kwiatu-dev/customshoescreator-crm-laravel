<template>
  <div class="card">
    <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
      <div><slot /></div>
      <div v-if="data" class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
        <span>{{ data['value'] }}</span>
        <span class="text-xs" :class="{'text-green-500': data['arrow'] === 'up', 'text-rose-500': data['arrow'] === 'down'}">
          <font-awesome-icon v-if="data['arrow'] === 'up'" :icon="['fas', 'caret-up']" />
          <font-awesome-icon v-if="data['arrow'] === 'down'" :icon="['fas', 'caret-down']" />
          {{ data['percentage'] }}%
        </span>
      </div>
      <div v-else class="loading-placeholder" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  data: {
    type: [Object, null],
    required: true,
  },
})
</script>

<style scoped>
.loading-placeholder {
  @apply bg-gray-200 dark:bg-gray-700 rounded-md shadow-sm w-full h-6 mt-2 animate-pulse;
}
</style>