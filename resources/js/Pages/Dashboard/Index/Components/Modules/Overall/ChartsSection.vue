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
import { useProjectsTypeBreakdown } from '@/Composables/useProjectsTypeBreakdown.js'
import { useMonthlyFinancialStats } from '@/Composables/useMonthlyFinancialStats'
import { inject, ref } from 'vue'
import { useMonthlyNewProjectsCount } from '@/Composables/useMonthlyNewProjectsCount'
import { useMonthlyCompletedProjectsCount } from '@/Composables/useMonthlyCompletedProjectsCount'
import { useIntersectionObserver } from '@vueuse/core'

const projectYears = inject('project_years')
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
    data.datasets.push({
      label: year,
      data: (await useProjectsTypeBreakdown(year)).data,
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

useIntersectionObserver(
  doughnutChart,
  ([{ isIntersecting }]) => {
    if (isIntersecting && doughnutChartData.value === null) {
      loadDoughnutChartData()
    }
  },
)

useIntersectionObserver(
  yearlyBarChart,
  ([{ isIntersecting }]) => {
    if (isIntersecting && yearlyBarChartData.value === null) {
      loadYearlyBarChartData()
    }
  },
)

useIntersectionObserver(
  lineChart01,
  ([{ isIntersecting }]) => {
    if (isIntersecting && lineChartData01.value === null) {
      loadLineChartData01()
    }
  },
)

useIntersectionObserver(
  lineChart02,
  ([{ isIntersecting }]) => {
    if (isIntersecting && lineChartData02.value === null) {
      loadLineChartData02()
    }
  },
)
</script>