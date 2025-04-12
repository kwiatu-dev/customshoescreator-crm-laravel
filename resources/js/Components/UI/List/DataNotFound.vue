<template>
  <div class="bg-gray-100 dark:bg-gray-800 rounded-md p-16 mt-4 shadow-md">     
    <h2 class="text-center text-xl md:text-2xl font-bold mt-2 text-gray-600 dark:text-gray-300">
      Nie znaleziono danych spełniających podane kryteria
    </h2>
    <div class="text-center mt-8">
      <button class="btn-primary px-8" @click="resetFilters">Wyczyść filtr</button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  filters: Object,
  columns: Object,
})

const emit = defineEmits(['reset-filters'])

const formatFilterName = (filterKey) => {
  const [columnName, rangeType] = filterKey.split('_')
  const columnLabel = props.columns?.[columnName]?.label
  let value = ''
  
  if (columnLabel) {
    value += columnLabel
  }

  if (rangeType === 'start') {
    value += ' od'
  }
  else if(rangeType === 'end') {
    value += ' do'
  }

  if (!value) {
    value = filterKey
  }

  return value
}

const resetFilters = () => {
  emit('reset-filters')
}
</script>
