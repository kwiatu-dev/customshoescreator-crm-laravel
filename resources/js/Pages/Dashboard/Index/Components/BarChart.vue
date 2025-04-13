<template>
  <div class="relative">
    <div class="w-full bg-white dark:bg-gray-800 rounded-md md:p-8 p-4 shadow-md border border-solid border-gray-300 chart-container">
      <slot name="header" />
      <ChartNavButtons v-if="hasNav" :labels="labels" @label_click="toggleDataset($event)" />
      <div style="height: 500px;" class="chart-inner">
        <Bar ref="bar" :data="chartData" :options="options" />
      </div>
    </div>
  </div>
</template>
  
<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { Bar } from 'vue-chartjs'
import { ref, onMounted, nextTick } from 'vue'
  
const props = defineProps({
  data: { 
    required: true, 
    type: Object, 
  },
  options: { 
    required: true, 
    type: Object, 
  },
  colors: { 
    required: true, 
    type: Object, 
  },
  hasNav: { 
    required: false, 
    default: true,
    type: Boolean, 
  },
})
  
const bar = ref(null)
const labels = ref({})
const chartData = ref({ datasets: [] })
    
const toggleDataset = (index) => {
  const meta = bar.value.chart.getDatasetMeta(index)
  meta.hidden = !meta.hidden 
  bar.value.chart.update()  
  labels.value[index].visible = !labels.value[index].visible
}
  
const injectDatasetsProperties = (data) => {
  const canvas = bar.value.chart.canvas
  const ctx = canvas.getContext('2d')
  
  for (const [index, dataset] of data.datasets.entries()) {
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height)
  
    gradient.addColorStop(0, props.colors[index].backgroundColor.replace('{opacity}', '0.9'))
    gradient.addColorStop(0.5, props.colors[index].backgroundColor.replace('{opacity}', '0.5'))
    gradient.addColorStop(1, props.colors[index].backgroundColor.replace('{opacity}', '0.2'))
  
    dataset.pointBackgroundColor = 'rgb(209 213 219)'
    dataset.pointBorderColor = 'rgb(209 213 219)'
    dataset.borderWidth = 1
    dataset.tension = 0.5
    dataset.fill = true
    dataset.backgroundColor = gradient
  }
  
  return data
}
    
onMounted(async () => {
  await nextTick()
  labels.value = props.data.datasets.map(dataset => ({ value: dataset.label, visible: true }))
  chartData.value = injectDatasetsProperties(props.data)
})
</script>

<style scoped>
@media(max-width: 768px) {
  .chart-container {
    overflow-x: auto;

  }

  .chart-inner {
    min-width: 800px;
  }
}
</style>