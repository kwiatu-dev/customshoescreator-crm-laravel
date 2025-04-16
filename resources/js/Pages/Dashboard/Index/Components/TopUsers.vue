<template>
  <div class="col-span-12 md:col-span-6 card flex flex-col justify-between">
    <ViewToggle v-model="view" :options="['Ostatni miesiąc', 'Od zawsze']" />
    <div class="flex flex-col flex-nowrap justify-center items-center my-4">
      <h2 class="!text-xl !text-gray-700 dark:!text-gray-500">Użytkownicy</h2>
      <p class="text-center -mt-2 text-sm text-gray-400">Wygenerowany przychód</p>
    </div>
    <div class="grid grid-cols-12 gap-2 mt-8" style="min-height: 356px;">
      <div class="col-span-4 flex flex-col items-center justify-end">
        <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
          <div v-if="data?.[1]">
            <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 dark:bg-gray-400 rounded-full shadow-sm text-white" style="font-size: 20px;" />
          </div>
          <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">{{ data?.[1]?.['full_name'] || 'BRAK' }}</div>
        </div>
        <div 
          style="height: 150px;"
          class="bg-green-200 dark:bg-green-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
        >
          <font-awesome-icon :icon="['fas', '2']" />
          <div class="absolute top-2 left-2 w-1 h-1 bg-green-400 rounded-full" />
          <div class="absolute top-2 right-2 w-1 h-1 bg-green-400 rounded-full" />
          <div class="absolute bottom-2 left-2 w-1 h-1 bg-green-400 rounded-full" />
          <div class="absolute bottom-2 right-2 w-1 h-1 bg-green-400 rounded-full" />
        </div>
      </div>
      <div class="col-span-4 flex flex-col items-center justify-end">
        <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
          <div v-if="data?.[0]">
            <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 dark:bg-gray-400  rounded-full shadow-sm text-white" style="font-size: 20px;" />
          </div>
          <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">{{ data?.[0]?.['full_name'] || 'BRAK' }}</div>
        </div>
        <div 
          style="height: 200px;"
          class="bg-rose-200 dark:bg-rose-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
        >
          <font-awesome-icon :icon="['fas', 'trophy']" />
          <div class="absolute top-2 left-2 w-1 h-1 bg-rose-400 rounded-full" />
          <div class="absolute top-2 right-2 w-1 h-1 bg-rose-400 rounded-full" />
          <div class="absolute bottom-2 left-2 w-1 h-1 bg-rose-400 rounded-full" />
          <div class="absolute bottom-2 right-2 w-1 h-1 bg-rose-400 rounded-full" />
        </div>
      </div>
      <div class="col-span-4 flex flex-col items-center justify-end">
        <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
          <div v-if="data?.[2]">
            <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 dark:bg-gray-400 rounded-full shadow-sm text-white" style="font-size: 20px;" />
          </div>
          <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">{{ data?.[2]?.['full_name'] || 'BRAK' }}</div>
        </div>
        <div 
          style="height: 100px;"
          class="bg-sky-200 dark:bg-sky-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
        >
          <font-awesome-icon :icon="['fas', '3']" />
          <div class="absolute top-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
          <div class="absolute top-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
          <div class="absolute bottom-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
          <div class="absolute bottom-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
        </div>
      </div>
    </div>
    <div>
      <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
          <div class="p-2 bg-rose-300 dark:bg-rose-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', 'trophy']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 dark:text-gray-400 text-md font-medium">{{ data?.[0]?.['full_name'] || 'BRAK' }}</div>
        </div>
        <table class="text-left border-collapse md:w-3/4 w-full">
          <thead>
            <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
              <th class="py-2 px-4 border-b">Przychód</th>
              <th class="py-2 px-4 border-b">Zakończone projekty</th>
              <th class="py-2 px-4 border-b">Średni czas realizacji</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-gray-700 text-sm">
              <td class="py-2 px-4 border-b">{{ data?.[0]?.['total_income'] ? `${data?.[0]?.['total_income']} zł` : '-' }}</td>
              <td class="py-2 px-4 border-b">{{ data?.[0]?.['project_count'] ? data?.[0]?.['project_count'] : '-' }}</td>
              <td class="py-2 px-4 border-b">{{ data?.[0]?.['avg_completion_days'] ? `${data?.[0]?.['avg_completion_days']} dni` : '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="w-full bg-gray-200 dark:bg-gray-700 my-4" style="height: 1px;" />
      <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
          <div class="p-2 bg-green-300 dark:bg-green-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', '2']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 dark:text-gray-400 text-md font-medium">{{ data?.[1]?.['full_name'] || 'BRAK' }}</div>
        </div>
        <table class="text-left border-collapse md:w-3/4 w-full">
          <thead>
            <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
              <th class="py-2 px-4 border-b">Przychód</th>
              <th class="py-2 px-4 border-b">Zakończone projekty</th>
              <th class="py-2 px-4 border-b">Średni czas realizacji</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-gray-700 text-sm">
              <td class="py-2 px-4 border-b">{{ data?.[1]?.['total_income'] ? `${data?.[1]?.['total_income']} zł` : '-' }}</td>
              <td class="py-2 px-4 border-b">{{ data?.[1]?.['project_count'] ? data?.[1]?.['project_count'] : '-' }}</td>
              <td class="py-2 px-4 border-b">{{ data?.[1]?.['avg_completion_days'] ? `${data?.[1]?.['avg_completion_days']} dni` : '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="w-full bg-gray-200 dark:bg-gray-700 my-4" style="height: 1px;" />
      <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
          <div class="p-2 bg-sky-300 dark:bg-sky-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', '3']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 dark:text-gray-400 text-md font-medium">{{ data?.[2]?.['full_name'] || 'BRAK' }}</div>
        </div>
        <table class="text-left border-collapse md:w-3/4 w-full">
          <thead>
            <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
              <th class="py-2 px-4 border-b">Przychód</th>
              <th class="py-2 px-4 border-b">Zakończone projekty</th>
              <th class="py-2 px-4 border-b">Średni czas realizacji</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-gray-700 text-sm">
              <td class="py-2 px-4 border-b">{{ data?.[2]?.['total_income'] ? `${data?.[2]?.['total_income']} zł` : '-' }}</td>
              <td class="py-2 px-4 border-b">{{ data?.[2]?.['project_count'] ? data?.[2]?.['project_count'] : '-' }}</td>
              <td class="py-2 px-4 border-b">{{ data?.[2]?.['avg_completion_days'] ? `${data?.[2]?.['avg_completion_days']} dni` : '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useTopUsers } from '@/Composables/useTopUsers'
import ViewToggle from '@/Pages/Dashboard/Index/Components/ViewToggle.vue'
import dayjs from 'dayjs'
import { computed, onMounted, ref, watch } from 'vue'
import { debounce } from 'lodash'

const data = ref([])
const view = ref(0)

const range = computed(() => {
  if (view.value === 0) {
    const startOfLastMonth = dayjs().subtract(1, 'month').startOf('month')
    const endOfLastMonth = dayjs().subtract(1, 'month').endOf('month')
    return { from: startOfLastMonth.format('YYYY-MM-DD'), to: endOfLastMonth.format('YYYY-MM-DD') }
  }
  else if (view.value === 1) {
    return null
  }

  return null
})

onMounted(async () => {
  data.value = await useTopUsers(range.value)
})

watch(range, debounce(async () => {
  data.value = await useTopUsers(range.value)
}, 1000))
</script>

<style scoped>
table {
  @apply w-full text-left border-collapse;
}

th, td {
  @apply py-2 px-4 border-b dark:border-gray-600 border-gray-200;
}

th {
  @apply bg-gray-200 text-gray-600 text-sm font-medium border border-gray-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-400; 
}

td {
  @apply text-gray-700 text-sm dark:text-gray-300;
}
</style>