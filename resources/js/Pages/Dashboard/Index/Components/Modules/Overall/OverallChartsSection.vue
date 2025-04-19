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
import '@/Components/UI/Charts/ChartRegister.js'
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import LineChart from '@/Components/UI/Charts/LineChart.vue'
import DoughnutChart from '@/Components/UI/Charts/DoughnutChart.vue'
import YearlyBarChart from '@/Components/UI/Charts/YearlyBarChart.vue'
import { ref } from 'vue'
import { getMonthlyCompletedProjectsCountChartData, getMonthlyFinancialStatsChartData, getMonthlyNewProjectsCountChartData, getProjectsTypeBreakdownChartData } from '@/Helpers/stats'
import { useElementInViewPortVisibility } from '@/Composables/useElementInViewPortVisibility'

const doughnutChartData = ref(null)
const doughnutChart = ref(null)
const yearlyBarChartData = ref(null)
const yearlyBarChart = ref(null)
const lineChartData01 = ref(null)
const lineChart01 = ref(null)
const lineChartData02 = ref(null)
const lineChart02 = ref(null)

useElementInViewPortVisibility(doughnutChart, async () => {
  if (doughnutChartData.value === null)
    doughnutChartData.value = await getProjectsTypeBreakdownChartData()
})

useElementInViewPortVisibility(yearlyBarChart, async () => {
  if (yearlyBarChartData.value === null)
    yearlyBarChartData.value = await getMonthlyFinancialStatsChartData()
})

useElementInViewPortVisibility(lineChart01, async () => {
  if (lineChartData01.value === null)
    lineChartData01.value = await getMonthlyNewProjectsCountChartData()
})

useElementInViewPortVisibility(lineChart02, async () => {
  if (lineChartData02.value === null)
    lineChartData02.value = await getMonthlyCompletedProjectsCountChartData()
})
</script>