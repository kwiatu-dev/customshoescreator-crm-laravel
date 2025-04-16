<template>
  <div class="relative">
    <div class="w-full bg-white dark:bg-gray-800 rounded-md md:p-8 p-4 shadow-md border dark:border-gray-600 border-solid border-gray-300">
      <slot name="nav" />
      <ChartNavButtons  
        v-if="!hasNavSlot"
        :labels="chartNavLabels" 
        @label_click="toggleDataset($event)"
      />
      <div style="height: 500px;">
        <Doughnut
          ref="doughnut" 
          :data="chartData || { datasets: [] }" 
          :options="chartOptions"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { Doughnut } from 'vue-chartjs'
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useTheme } from '@/Composables/useTheme'
import { colors as doughnutChartColors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'
import { options as doughnutChartOptions } from '@/Pages/Dashboard/Index/Components/DoughnutChartOptions.js'
import { useSlots } from 'vue'

const slots = useSlots()
const hasNavSlot = computed(() => !!slots.nav)

const doughnut = ref(null)
const theme = useTheme()
const chartColors = computed(() => doughnutChartColors({ theme: theme.value }))
const chartOptions = computed(() => doughnutChartOptions({...props.options, theme: theme.value }))
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
  const chart = doughnut.value.chart

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
  const chartArea = doughnut.value.chart.chartArea
  const canvas = doughnut.value.chart.canvas
  const ctx = canvas.getContext('2d')

  for (const dataset of datasets) {
    let backgroundColors = []

    for (const [index, _] of dataset.data.entries()) {
      const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)

      gradient.addColorStop(0, chartColors.value[index].backgroundColor.replace('{opacity}', '0.9'))
      gradient.addColorStop(0.5, chartColors.value[index].backgroundColor.replace('{opacity}', '0.5'))
      gradient.addColorStop(1, chartColors.value[index].backgroundColor.replace('{opacity}', '0.2'))

      backgroundColors.push(gradient)
    }

    dataset.backgroundColor = backgroundColors
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