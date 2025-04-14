<template>
  <BarChart 
    :key="barChartKey"
    :data="barChartData" 
    :options="options" 
    :has-nav="false"
  >
    <template #header>
      <ChartNavButtons 
        :labels="barChartLabels" 
        @label_click="toggleDataset($event)"
      />
    </template>
  </BarChart>
</template>

<script setup>
import { ref } from 'vue'
import BarChart from '@/Pages/Dashboard/Index/Components/BarChart.vue'
import ChartNavButtons from '@/Pages/Dashboard/Index/Components/ChartNavButtons.vue'

const props = defineProps({
  data: { 
    required: true, 
    type: Object, 
  },
  options: { 
    required: true, 
    type: Object, 
  },
})

const barChartKey = ref(0)

const toggleDataset = (index) => {
  const year = Object.keys(props.data)[index]
  const data = Object.values(props.data)[index]

  if (year === barChartLabels.value.find(label => label.visible).value)
    return 

  barChartData.value = data
  barChartKey.value++

  barChartLabels.value.forEach((label, i) => {
    label.visible = i === index
  })
}

const lastElement = Object.keys(props.data).slice(-1)[0]
const barChartData = ref(props.data[lastElement])
const barChartLabels = ref(Object.keys(props.data).map(year => ({ value: year, visible: year == lastElement })))
</script>