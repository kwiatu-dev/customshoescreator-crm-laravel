<template>
  <div class="w-full bg-gray-800 rounded-md md:p-8 p-4 shadow-md">
    <div class="title text-center mb-8">
      Przykładowy wykres
    </div>
    <ChartNavButtons :labels="labels" @label_click="toggleDataset($event)" />
    <div class="doughnut-height">
      <Doughnut ref="doughnut" :data="chartData" :options="options" />
    </div>
  </div>
</template>

<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { options } from '@/Pages/Dashboard/Index/Components/DoughnutChartOptions.js'
import { colors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'
import { Doughnut } from 'vue-chartjs'
import { ref, onMounted, nextTick } from 'vue'

const props = defineProps({
  data: { 
    required: true, 
    type: Object, 
  },
})

const doughnut = ref(null)
const labels = ref({})
const chartData = ref({ datasets: [] })

const toggleDataset = (index) => {
  const meta = doughnut.value.chart.getDatasetMeta(0)
  meta.data[index].hidden = !meta.data[index].hidden
  doughnut.value.chart.update()
  labels.value[index].visible = !labels.value[index].visible
}

const injectDatasetsProperties = (data) => {
  const canvas = doughnut.value.chart.canvas
  const ctx = canvas.getContext('2d')

  for (const dataset of data.datasets) {
    let backgroundColors = []

    for (const [index, _] of dataset.data.entries()) {
      const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height)

      gradient.addColorStop(0, colors[index].backgroundColor.replace('{opacity}', '0.9'))
      gradient.addColorStop(0.5, colors[index].backgroundColor.replace('{opacity}', '0.5'))
      gradient.addColorStop(1, colors[index].backgroundColor.replace('{opacity}', '0.2'))

      backgroundColors.push(gradient)
    }

    dataset.pointBackgroundColor = 'rgb(209 213 219)'
    dataset.pointBorderColor = 'rgb(209 213 219)'
    dataset.borderWidth = 0
    dataset.borderRadius = 10
    dataset.cutout = '70%'
    dataset.spacing = 5
    dataset.backgroundColor = backgroundColors
  }

  return data
}

onMounted(async () => {
  await nextTick()
  labels.value = props.data.labels.map(label => ({ value: label, visible: true }))
  chartData.value = injectDatasetsProperties(props.data)
})

//todo - dodać napis w środku donata z informacją ile procent oczekuje na na wypłacenie
</script>

<style scoped>
.doughnut-height{
  height: unset;
}

@media(min-width: 768px) {
  .doughnut-height{
    height: 500px;
  }
}
</style>