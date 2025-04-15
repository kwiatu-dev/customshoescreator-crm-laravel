<template>
  <div class="card">
    <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
      <div class="p-2 bg-indigo-400 dark:bg-indigo-500 w-16 h-16 rounded-xl flex items-center justify-center">
        <font-awesome-icon :icon="icon" class="text-gray-50 dark:text-gray-200" style="font-size: 30px;" />
      </div>
      <div class="text-gray-800 font-medium text-xl dark:text-gray-400">Pieniądze</div>
    </div>
    <div v-if="data" class="flex md:flex-col md:gap-2 gap-8 flex-wrap">
      <div v-for="(item, name) in data" :key="name">
        <div v-if="item.hasOwnProperty('arrow') && item.hasOwnProperty('percentage') && item.hasOwnProperty('value')">
          <div class="text-gray-400 dark:text-gray-500">{{ labels[name] }}</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>{{ item['value'] }} {{ units?.[name] || '' }}</span>
            <span class="text-xs" :class="{'text-green-500': item['arrow'] === 'up', 'text-rose-500': item['arrow'] === 'down'}">
              <font-awesome-icon v-if="item['arrow'] === 'up'" :icon="['fas', 'caret-up']" />
              <font-awesome-icon v-if="item['arrow'] === 'down'" :icon="['fas', 'caret-down']" />
              {{ item['percentage'] }}%
            </span>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="flex md:flex-col md:gap-2 gap-8 flex-wrap">
      <div v-for="(item, name) in labels" :key="name">
        <div class="text-gray-400 dark:text-gray-500">{{ labels[name] }}</div>
        <div class="loading-placeholder" />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  data: {
    type: [Object, null],
    required: true,
  },
  labels: {
    type: Object,
    required: true,
  },
  icon: {
    type: Array,
    required: true,
  },
  units: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped>
.loading-placeholder {
  @apply bg-gray-200 dark:bg-gray-700 rounded-md shadow-sm w-full h-6 mt-2 animate-pulse;
}
</style>