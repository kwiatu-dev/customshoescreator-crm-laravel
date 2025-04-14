<template>
  <div class="relative">
    <div class="w-full bg-white dark:bg-gray-800 rounded-md md:p-8 p-4 shadow-md border dark:border-gray-600 border-solid border-gray-300">
      <slot name="header" />
      <ChartNavButtons v-if="hasNav" :labels="labels" @label_click="toggleDataset($event)" />
      <div class="doughnut-height">
        <Doughnut ref="doughnut" :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { Doughnut } from 'vue-chartjs'
import { ref, onMounted, nextTick, computed, watch } from 'vue'
import { useTheme } from '@/Composables/useTheme'
import { colors as doughnutChartColors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'
import { options as doughnutChartOptions } from '@/Pages/Dashboard/Index/Components/DoughnutChartOptions.js'

const theme = useTheme()
const chartColors = computed(() => doughnutChartColors({ theme: theme.value }))
const chartOptions = computed(() => doughnutChartOptions({...props.options, theme: theme.value }))

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

const doughnut = ref(null)
const labels = ref({})
const chartData = ref({ datasets: [] })

const toggleDataset = (index) => {
  const chart = doughnut.value.chart
  
  chart.data.datasets.forEach((_, i) => {
    const meta = chart.getDatasetMeta(i)
    meta.hidden = i !== index 
    labels.value[i].visible = i === index 
  })

  chart.update()
}

const injectDatasetsProperties = (data) => {
  const chartArea = doughnut.value.chart.chartArea
  const canvas = doughnut.value.chart.canvas
  const ctx = canvas.getContext('2d')

  for (const dataset of data.datasets) {
    let backgroundColors = []

    for (const [index, _] of dataset.data.entries()) {
      const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)

      gradient.addColorStop(0, chartColors.value[index].backgroundColor.replace('{opacity}', '0.9'))
      gradient.addColorStop(0.5, chartColors.value[index].backgroundColor.replace('{opacity}', '0.5'))
      gradient.addColorStop(1, chartColors.value[index].backgroundColor.replace('{opacity}', '0.2'))

      backgroundColors.push(gradient)
    }

    dataset.backgroundColor = backgroundColors
  }

  return data
}

onMounted(async () => {
  await nextTick()
  labels.value = props.data.datasets.map((dataset, index) => ({ value: dataset.label, visible: index === props.data.datasets.length - 1 }))

  props.data.datasets.forEach((dataset, index) => {
    dataset.hidden = index !== props.data.datasets.length - 1
  })

  chartData.value = injectDatasetsProperties(props.data)
})

watch(() => chartColors.value, async () => {
  chartData.value = { labels: [], datasets: [] }
  await nextTick()
  chartData.value = injectDatasetsProperties(props.data)
})
</script>

<style scoped>
.doughnut-height{
  height: 500px;
}

@media(max-width: 768px) {
  .doughnut-height{
    height: unset;
  }
}
</style>