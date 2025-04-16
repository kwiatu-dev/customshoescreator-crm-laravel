<template>
  <div class="relative">
    <div class="w-full bg-white dark:bg-gray-800 rounded-md md:p-8 p-4 shadow-md border border-solid dark:border-gray-600 border-gray-300 chart-container">
      <slot name="nav" />
      <ChartNavButtons 
        v-if="!hasNavSlot"
        :labels="chartNavLabels" 
        @label_click="toggleDataset($event)"
      />
      <div style="height: 500px;" class="chart-inner">
        <Bar 
          ref="bar" 
          :data="chartData || { datasets: [] }" 
          :options="chartOptions"
        />
      </div>
    </div>
  </div>
</template>
  
<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { Bar } from 'vue-chartjs'
import { ref, onMounted, nextTick, computed, watch } from 'vue'
import { colors as barChartColors } from '@/Pages/Dashboard/Index/Components/ChartColors'
import { useTheme } from '@/Composables/useTheme'
import { options as barChartOptions } from '@/Pages/Dashboard/Index/Components/BarChartOptions.js'
import { useSlots } from 'vue'

const slots = useSlots()
const hasNavSlot = computed(() => !!slots.nav)

const bar = ref(null)
const theme = useTheme()
const chartColors = computed(() => barChartColors({ theme: theme.value }))
const chartOptions = computed(() => barChartOptions({...props.options, theme: theme.value, showTicks: !!chartData.value, showGrid: !!chartData.value }))
const chartData = ref(null)
const chartNavLabels = ref(null)
  
const props = defineProps({
  data: { 
    required: true, 
    type: [Object, null], 
  },
  options: { 
    required: true, 
    type: Object, 
  },
})
   
const toggleDataset = (label) => {
  const chart = bar.value.chart

  Object.keys(chartNavLabels.value).forEach((label) => {
    chartNavLabels.value[label].active = false
  })

  chartNavLabels.value[label].active = true

  chart.data.datasets.forEach((dataset, i) => {
    const meta = chart.getDatasetMeta(i)
    meta.hidden = !isDatasetActive(dataset.label)
  })

  chart.update()
}
  
const injectDatasetsProperties = (datasets) => {
  const chartArea = bar.value.chart.chartArea
  const canvas = bar.value.chart.canvas
  const ctx = canvas.getContext('2d')
  
  for (const [index, dataset] of datasets.entries()) {
    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)
  
    gradient.addColorStop(0, chartColors.value[index].backgroundColor.replace('{opacity}', '0.9'))
    gradient.addColorStop(0.5, chartColors.value[index].backgroundColor.replace('{opacity}', '0.5'))
    gradient.addColorStop(1, chartColors.value[index].backgroundColor.replace('{opacity}', '0.2'))
  
    dataset.backgroundColor = gradient
    dataset.hidden = !isDatasetActive(dataset.label)
  }
  
  return datasets
}

const isDatasetActive = (label) => {
  if (hasNavSlot.value) {
    return true
  }

  if (chartNavLabels.value && typeof chartNavLabels.value === 'object') {
    return chartNavLabels.value[label]?.active === true
  }

  return false
}

const buildChartData = (data) => {
  if (data && data.datasets) {
    buildNavLabels(data.datasets)
    injectDatasetsProperties(data.datasets)
    return data
  }

  return null
}

const buildNavLabels = (datasets) => {
  const flag = chartNavLabels.value === null

  chartNavLabels.value = datasets.reduce((acc, dataset, index) => {
    if (dataset.label) {
      acc[dataset.label] = {
        active: flag ? index === datasets.length - 1 : isDatasetActive(dataset.label),
      }
    }
    return acc
  }, {})
}
    
onMounted(async () => { 
  await nextTick()
  chartData.value = buildChartData(props.data)
})

watch(() => props.data, () => { 
  chartData.value = buildChartData(props.data) 
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