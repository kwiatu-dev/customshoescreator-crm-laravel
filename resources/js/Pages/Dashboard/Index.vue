<template>
  <div class="grid grid-cols-12 gap-4">
    <!-- DLA UŻYTKOWNIKA ZWYKŁEGO  -->



    <DoughnutChart :data="doughnutChartData" class="2xl:col-span-4 col-span-12" />
    <!-- DOCHÓD  -->
    <LineChart :data="lineChartData" class="2xl:col-span-8 col-span-12" />
    <!-- ILOŚĆ WYKONANYCH ZLECEŃ  -->
    <!-- ILOŚĆ ŚREDNI CZAS WYKONYWANIA JEDNEGO ZLECENIA  -->
  </div>
</template>
  
<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import DoughnutChart from '@/Pages/Dashboard/Index/Components/DoughnutChart.vue'

const lineChartData = {
  labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
  datasets: [
    {
      label: 'rok 2024',
      data: [60, 55, 32, 10, 2, 12, 53, 23, 40, 55, 13, 100],
    },
    {
      label: 'rok 2023',
      data: [40, 39, 10, 40, 45, 80, 40, 72, 88, 105, 13, 0],
    },
  ],
}

const doughnutChartData = {
  labels: ['Rozliczone', 'Oczekujące'],
  datasets: [
    {
      data: [9000, 14000],
    },
  ],
}
</script>
  