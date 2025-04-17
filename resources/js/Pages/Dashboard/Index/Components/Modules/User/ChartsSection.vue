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
  <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość nowych projektów</h2>
    <LineChart 
      ref="lineChart01"
      :data="lineChartData01" 
      :options="{ local: 'pl-PL', currency: null, stepSize: 1 }" 
      active="all"
    />
  </div>
  <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość zakończonych projektów</h2>
    <LineChart 
      ref="lineChart02"
      :data="lineChartData02" 
      :options="{ local: 'pl-PL', currency: null, stepSize: 1 }" 
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
import { nextTick, onMounted, ref } from 'vue'
import { useProjectYears } from '@/Composables/useProjectYears'
import { useProjectsTypeBreakdown } from '@/Composables/useProjectsTypeBreakdown.js'
import { useMonthlyFinancialStats } from '@/Composables/useMonthlyFinancialStats'
import { useMonthlyNewProjectsCount } from '@/Composables/useMonthlyNewProjectsCount'
import { useMonthlyCompletedProjectsCount } from '@/Composables/useMonthlyCompletedProjectsCount'
import { useAuthUser } from '@/Composables/useAuthUser'
import { useIntersectionObserver } from '@vueuse/core'
import dayjs from 'dayjs'

const auth = useAuthUser()
const projectYears = ref(null)
const doughnutChartData = ref(null)
const doughnutChart = ref(null)
const yearlyBarChartData = ref(null)
const yearlyBarChart = ref(null)
const lineChartData01 = ref(null)
const lineChart01 = ref(null)
const lineChartData02 = ref(null)
const lineChart02 = ref(null)

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
    data[year] = await useMonthlyFinancialStats(year, auth.value.id)
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
      data: (await useMonthlyNewProjectsCount(year, auth.value.id)).data,
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
      data: (await useMonthlyCompletedProjectsCount(year, auth.value.id)).data,
    })
  }

  lineChartData02.value = data
}

onMounted(async () => {
  await nextTick()
  projectYears.value = await useProjectYears()
})

useIntersectionObserver(
  doughnutChart,
  ([{ isIntersecting }]) => {
    if (isIntersecting) {
      loadDoughnutChartData()
    }
  }
)

useIntersectionObserver(
  yearlyBarChart,
  ([{ isIntersecting }]) => {
    if (isIntersecting) {
      loadYearlyBarChartData()
    }
  }
)

useIntersectionObserver(
  lineChart01,
  ([{ isIntersecting }]) => {
    if (isIntersecting) {
      loadLineChartData01()
    }
  }
)

useIntersectionObserver(
  lineChart02,
  ([{ isIntersecting }]) => {
    if (isIntersecting) {
      loadLineChartData02()
    }
  }
)
</script>