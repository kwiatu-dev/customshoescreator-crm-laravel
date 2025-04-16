<template>
  <SectionTitle class="col-span-12">
    Wykresy
  </SectionTitle>
  <div class="col-span-12 md:col-span-4 card">
    <h2>Projekty wg. kategorii</h2>
    <DoughnutChart
      ref="doughnutChart"
      :data="doughnutChartData" 
      :options="{ local: 'pl-PL', currency: null }" 
    />
  </div>
  <div class="col-span-12 md:col-span-8 card">
    <h2>Finanse</h2>
    <YearlyBarChart 
      ref="yearlyBarChart"
      :data="yearlyBarChartData" 
      :options="{ local: 'pl-PL', currency: 'PLN', }" 
    />
  </div>
  <!-- <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość nowych projektów</h2>
    <LineChart 
      :data="lineChartData" 
      :options="{ local: 'pl-PL', currency: null, }" 
    />
  </div> -->
  <!-- <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość zakończonych projektów</h2>
    <LineChart 
      :data="lineChartData" 
      :options="{ local: 'pl-PL', currency: null, }" 
    />
  </div> -->
</template>

<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import DoughnutChart from '@/Pages/Dashboard/Index/Components/DoughnutChart.vue'
import YearlyBarChart from '@/Pages/Dashboard/Index/Components/YearlyBarChart.vue'
import { useProjectYears } from '@/Composables/useProjectYears'
import { useProjectsTypeBreakdown } from '@/Composables/useProjectsTypeBreakdown.js'
import { useMonthlyFinancialStats } from '@/Composables/useMonthlyFinancialStats'
import { onMounted, ref } from 'vue'
import dayjs from 'dayjs'


const projectYears = ref(null)
const doughnutChart = ref(null)
const doughnutChartData = ref(null)
const yearlyBarChart = ref(null)
const yearlyBarChartData = ref(null)

const loadDoughnutChartData = async () => {
  const data = {
    labels: ['Renowacja butów', 'Personalizacja butów', 'Personalizacja ubrań', 'Haft ręczny', 'Haft komputerowy', 'Inne'],
    datasets: [],
  }

  for (const year of projectYears.value) {
    const from = dayjs(`${year}-01-01`).startOf('day').format('YYYY-MM-DD')
    const to = dayjs(`${year}-12-31`).endOf('day').format('YYYY-MM-DD')
    const range = { from, to }

    data.datasets.push({
      label: year,
      data: (await useProjectsTypeBreakdown(range)).data,
    })
  }

  doughnutChartData.value = data
}

const loadYearlyBarChartData = async () => {
  const data = {}

  for (const year of projectYears.value) {
    data[year] = await useMonthlyFinancialStats(year)
  }

  yearlyBarChartData.value = data
}

onMounted(async () => {
  projectYears.value = await useProjectYears()
  loadDoughnutChartData()
  loadYearlyBarChartData()
})

// const lineChartData = {
//   labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
//   datasets: [
//     {
//       label: '2023',
//       data: [40, 39, 10, 40, 45, 80, 40, 72, 88, 105, 13, 0],
//     },
//     {
//       label: '2024',
//       data: [60, 55, 32, 10, 2, 12, 53, 23, 40, 55, 13, 100],
//     },

//   ],
// }
</script>