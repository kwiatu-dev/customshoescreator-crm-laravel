<template>
  <div class="relative">
    <div class="w-full bg-white dark:bg-gray-800 rounded-md md:p-8 p-4 shadow-md border border-solid border-gray-300 dark:border-gray-600 chart-container">
      <slot name="header" />
      <ChartNavButtons v-if="hasNav" :labels="labels" @label_click="toggleDataset($event)" />
      <div style="height: 500px;" class="chart-inner">
        <Line ref="line" :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { Line } from 'vue-chartjs'
import { ref, onMounted, nextTick, computed, watch } from 'vue'
import { useTheme } from '@/Composables/useTheme'
import { options as lineChartOptions } from '@/Pages/Dashboard/Index/Components/LineChartOptions.js'
import { colors as lineChartColors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'

const theme = useTheme()
const chartColors = computed(() => lineChartColors({ theme: theme.value }))
const chartOptions = computed(() => lineChartOptions({...props.options, theme: theme.value }))

const props = defineProps({
  data: { 
    required: true, 
    type: Object, 
  },
  options: { 
    required: true, 
    type: Object, 
  },
  hasNav: { 
    required: false, 
    default: true,
    type: Boolean, 
  },
})

const line = ref(null)
const labels = ref({})
const chartData = ref({ datasets: [] })
  
const toggleDataset = (index) => {
  const meta = line.value.chart.getDatasetMeta(index)
  meta.hidden = !meta.hidden 
  line.value.chart.update()  
  labels.value[index].visible = !labels.value[index].visible
}

const injectDatasetsProperties = (data) => {
  const chartArea = line.value.chart.chartArea
  const canvas = line.value.chart.canvas
  const ctx = canvas.getContext('2d')

  for (const [index, dataset] of data.datasets.entries()) {
    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)

    gradient.addColorStop(0, chartColors.value[index].backgroundColor.replace('{opacity}', '0.9'))
    gradient.addColorStop(0.5, chartColors.value[index].backgroundColor.replace('{opacity}', '0.5'))
    gradient.addColorStop(1, chartColors.value[index].backgroundColor.replace('{opacity}', '0.2'))

    dataset.backgroundColor = gradient
  }

  return data
}
  
onMounted(async () => {
  await nextTick()
  labels.value = props.data.datasets.map(dataset => ({ value: dataset.label, visible: true }))
  chartData.value = injectDatasetsProperties(props.data)
})

watch(() => chartColors.value, async () => {
  chartData.value = { labels: [], datasets: [] }
  await nextTick()
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