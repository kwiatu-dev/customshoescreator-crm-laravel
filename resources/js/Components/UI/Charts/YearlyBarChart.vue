<template>
  <BarChart 
    :data="chartData" 
    :options="options" 
  >
    <template #nav>
      <ChartNavButtons 
        :labels="chartNavLabels" 
        @label_click="toggleDataset($event)"
      />
    </template>
  </BarChart>
</template>

<script setup>
import BarChart from '@/Components/UI/Charts/BarChart.vue'
import ChartNavButtons from '@/Components/UI/Charts/ChartNavButtons.vue'
import { nextTick, onMounted, ref, watch } from 'vue'

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
  Object.keys(chartNavLabels.value).forEach((label) => {
    chartNavLabels.value[label].active = false
  })

  chartNavLabels.value[label].active = true
  chartData.value = buildChartData(props.data)
}

const isDatasetActive = (label) => {
  if (chartNavLabels.value && typeof chartNavLabels.value === 'object') {
    return chartNavLabels.value[label]?.active === true
  }

  return false
}

const getActiveNavLabel = () => {
  if (chartNavLabels.value && typeof chartNavLabels.value === 'object') {
    const entry = Object.entries(chartNavLabels.value).find(
      ([_, data]) => data.active === true,
    )
    return entry ? entry[0] : null 
  }

  return null
}

const buildChartData = (data) => {
  if (data) {
    buildNavLabels(Object.keys(data))
    return data[getActiveNavLabel()]
  }

  return null
}

const buildNavLabels = (years) => {
  const flag = chartNavLabels.value === null

  chartNavLabels.value = years.reduce((acc, year, index) => {
    acc[year] = {
      active: flag ? index === years.length - 1 : isDatasetActive(year),
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