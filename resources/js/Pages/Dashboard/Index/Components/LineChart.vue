<template>
  <div class="w-full bg-gray-800 rounded-md md:p-8 p-4 shadow-md">
    <div class="title text-center mb-8">
      Przykładowy wykres
    </div>
    <ChartNavButtons :labels="labels" @label_click="toggleDataset($event)" />
    <div style="height: 500px;">
      <Line ref="line" :data="chartData" :options="options" />
    </div>
  </div>
</template>

<script setup>
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'
import { options } from '@/Pages/Dashboard/Index/Components/LineChartOptions.js'
import { colors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'
import { Line } from 'vue-chartjs'
import { ref, onMounted, nextTick } from 'vue'

const props = defineProps({
  data: { 
    required: true, 
    type: Object, 
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
  const canvas = line.value.chart.canvas
  const ctx = canvas.getContext('2d')

  for (const [index, dataset] of data.datasets.entries()) {
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height)

    gradient.addColorStop(0, colors[index].backgroundColor.replace('{opacity}', '0.9'))
    gradient.addColorStop(0.5, colors[index].backgroundColor.replace('{opacity}', '0.5'))
    gradient.addColorStop(1, colors[index].backgroundColor.replace('{opacity}', '0.2'))

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

//todo - usupełnić wykres prawdziwymi danymi, dodać wyświetlanie na ekranach z trybem jasnym
</script>