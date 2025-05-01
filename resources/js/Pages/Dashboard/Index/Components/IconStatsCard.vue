<template>
  <div class="card">
    <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
      <div class="p-2 bg-indigo-400 dark:bg-indigo-500 w-16 h-16 rounded-xl flex items-center justify-center">
        <font-awesome-icon :icon="icon" class="text-gray-50 dark:text-gray-200" style="font-size: 30px;" />
      </div>
      <div class="text-gray-800 font-medium text-xl dark:text-gray-400"><slot /></div>
    </div>
    <div class="flex flex-col sm:flex-row justify-between gap-2 md:flex-col">
      <div 
        v-for="(label, key) in labels" 
        :key="key"
        class="flex-1"
      >
        <div v-if="visibleForUser(key)">
          <div 
            v-if="data?.[key] && data[key].hasOwnProperty('arrow') && data[key].hasOwnProperty('percentage') && data[key].hasOwnProperty('value')"
            class="flex flex-col"
          >
            <div class="text-gray-400 dark:text-gray-500">{{ label }}</div>
            <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
              <span>{{ data[key]['value'] }} {{ units?.[key] || '' }}</span>
              <span class="text-xs" :class="{'text-green-500': data[key]['arrow'] === 'up', 'text-rose-500': data[key]['arrow'] === 'down'}">
                <font-awesome-icon v-if="data[key]['arrow'] === 'up'" :icon="['fas', 'caret-up']" />
                <font-awesome-icon v-if="data[key]['arrow'] === 'down'" :icon="['fas', 'caret-down']" />
                {{ data[key]['percentage'] }}%
              </span>
            </div>
          </div>
          <div v-else>
            <div class="text-gray-400 dark:text-gray-500">{{ label }}</div>
            <div class="loading-placeholder" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthUser } from '@/Composables/useAuthUser'

const props = defineProps({
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
  onlyForAdmin: {
    type: Object,
    required: false,
    default: () => ({}),
  },
})

const auth = useAuthUser()

const visibleForUser = (label) => {
  if (props.onlyForAdmin?.[label]) {
    return !!auth.value?.is_admin
  }

  return true
}
</script>

<style scoped>
.loading-placeholder {
  @apply bg-gray-200 dark:bg-gray-700 rounded-md shadow-sm w-full h-6 animate-pulse;
}
</style>