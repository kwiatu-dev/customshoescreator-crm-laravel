<template>
  <SectionTitle class="col-span-12">
    Wykresy
  </SectionTitle>
  <div class="col-span-12 md:col-span-4 card">
    <h2>Projekty wg. kategorii</h2>
    <DoughnutChart
      :data="doughnutChartData" 
      :options="{ local: 'pl-PL', currency: null }" 
    />
  </div>
  <div class="col-span-12 md:col-span-8 card">
    <h2>Finanse</h2>
    <YearlyBarChart 
      :data="yearlyBarChartData" 
      :options="{ local: 'pl-PL', currency: 'PLN', }" 
    />
  </div>
  <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość nowych projektów</h2>
    <LineChart 
      :data="lineChartData01" 
      :options="{ local: 'pl-PL', currency: null, }" 
      active="all"
    />
  </div>
  <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość zakończonych projektów</h2>
    <LineChart 
      :data="lineChartData02" 
      :options="{ local: 'pl-PL', currency: null, }" 
      active="all"
    />
  </div>
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
import { useMonthlyNewProjectsCount } from '@/Composables/useMonthlyNewProjectsCount'
import { useMonthlyCompletedProjectsCount } from '@/Composables/useMonthlyCompletedProjectsCount'

const projectYears = ref(null)
const doughnutChartData = ref(null)
const yearlyBarChartData = ref(null)
const lineChartData01 = ref(null)
const lineChartData02 = ref(null)

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

const loadLineChartData01 = async () => {
  const data = {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [],
  }

  for (const year of projectYears.value) {
    data.datasets.push({
      label: year,
      data: (await useMonthlyNewProjectsCount(year)).data,
    })
  }

  lineChartData01.value = data
}

const loadLineChartData02 = async () => {
  const data = {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [],
  }

  for (const year of projectYears.value) {
    data.datasets.push({
      label: year,
      data: (await useMonthlyCompletedProjectsCount(year)).data,
    })
  }

  lineChartData02.value = data
}

onMounted(async () => {
  projectYears.value = await useProjectYears()
  loadDoughnutChartData()
  loadYearlyBarChartData()
  loadLineChartData01()
  loadLineChartData02()
})
</script>