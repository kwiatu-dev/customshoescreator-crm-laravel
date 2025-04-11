<template>
  <div class="bg-gray-800 rounded-md p-4 mt-4">     
    <div class="text-center" style="font-size: 40px">
      <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
    </div> 
    <h2 class="text-center text-xl md:text-2xl font-bold mt-2">
      Nie znaleziono danych spełniających podane kryteria
    </h2>
    <!-- <div class="line mt-4 mb-4" />
    <div v-for="(filterValue, filterKey) in filters" :key="filterKey" class="text-center">
      {{ formatFilterName(filterKey) }} 
      <font-awesome-icon :icon="['fas', 'arrow-right']" /> 
      {{ filterValue }}
    </div>
    <div class="line mt-4 mb-4" /> -->
    <div class="text-center mt-4">
      <button class="btn-primary" @click="resetFilters">Wyczyść filtr</button>
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
