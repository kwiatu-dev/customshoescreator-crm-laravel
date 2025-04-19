<template>
  <div class="relative">
    <div class="w-full bg-white dark:bg-gray-800 rounded-md md:p-8 p-4 shadow-md border border-solid border-gray-300 dark:border-gray-600 chart-container">
      <slot name="nav" />
      <ChartNavButtons 
        v-if="!hasNavSlot"
        :labels="chartNavLabels" 
        @label_click="toggleDataset($event)"
      />
      <div style="height: 500px;" class="chart-inner">
        <Line 
          ref="line" 
          :data="chartData || { datasets: [] }" 
          :options="chartOptions"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import ChartNavButtons from '@/Components/UI/Charts/ChartNavButtons.vue'
import { Line } from 'vue-chartjs'
import { ref, onMounted, nextTick, computed, watch } from 'vue'
import { useTheme } from '@/Composables/useTheme'
import { options as lineChartOptions } from '@/Components/UI/Charts/LineChartOptions.js'
import { colors as lineChartColors } from '@/Components/UI/Charts/ChartColors.js'
import { useSlots } from 'vue'

const slots = useSlots()
const hasNavSlot = computed(() => !!slots.nav)

const line = ref(null)
const theme = useTheme()
const chartColors = computed(() => lineChartColors({ theme: theme.value }))
const chartOptions = computed(() => lineChartOptions({...props.options, theme: theme.value, showTicks: !!chartData.value, showGrid: !!chartData.value }))
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
  active: {
    required: false,
    type: String,
    default: 'last',
  },
})

const toggleDataset = (label) => {
  const chart = line.value.chart

  chartNavLabels.value[label].active = !chartNavLabels.value[label].active

  chart.data.datasets.forEach((dataset, i) => {
    const meta = chart.getDatasetMeta(i)
    meta.hidden = !isDatasetActive(dataset.label)
  })

  chart.update()
}
  
const injectDatasetsProperties = (datasets) => {
  const chartArea = line.value.chart.chartArea
  const canvas = line.value.chart.canvas
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
      if (props.active === 'last') {
        acc[dataset.label] = {
          active: flag ? index === datasets.length - 1 : isDatasetActive(dataset.label),
        }
      }
      else if (props.active === 'all'){
        acc[dataset.label] = {
          active: true,
        }
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