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
    <h2>Pieniądze</h2>
    <YearlyBarChart 
      :data="yearlyBarChartData" 
      :options="{ local: 'pl-PL', currency: 'PLN', }" 
    />
  </div>
  <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość nowych projektów</h2>
    <LineChart 
      :data="lineChartData" 
      :options="{ local: 'pl-PL', currency: null, }" 
    />
  </div>
  <div class="col-span-12 2xl:col-span-6 card">
    <h2>Ilość zakończonych projektów</h2>
    <LineChart 
      :data="lineChartData" 
      :options="{ local: 'pl-PL', currency: null, }" 
    />
  </div>
</template>

<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import DoughnutChart from '@/Pages/Dashboard/Index/Components/DoughnutChart.vue'
import YearlyBarChart from '@/Pages/Dashboard/Index/Components/YearlyBarChart.vue'

const yearlyBarChartData = {
  2023: {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [
      {
        label: 'Przychód',
        data: [1000, 1200, 1500, 1300, 1700, 2000, 2200, 2100, 1900, 1800, 1600, 1400],
      },
      {
        label: 'Dochód',
        data: [800, 1000, 1200, 1100, 1500, 1800, 2000, 1900, 1700, 1600, 1400, 1200],
      },
      {
        label: 'Wydatki',
        data: [200, 200, 300, 200, 200, 200, 200, 200, 200, 200, 200, 200],
      },
      {
        label: 'Zysk',
        data: [600, 800, 900, 900, 1300, 1600, 1800, 1700, 1500, 1400, 1200, 1000],
      },
    ],
  },
  2024: {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [
      {
        label: 'Przychód',
        data: [1200, 1400, 1700, 1500, 1900, 2200, 2400, 2300, 2100, 2000, 1800, 1600],
      },
      {
        label: 'Dochód',
        data: [1000, 1200, 1400, 1300, 1700, 2000, 2200, 2100, 1900, 1800, 1600, 1400],
      },
      {
        label: 'Wydatki',
        data: [200, 200, 300, 200, 200, 200, 200, 200, 200, 200, 200, 200],
      },
      {
        label: 'Zysk',
        data: [800, 1000, 1100, 1100, 1500, 1800, 2000, 1900, 1700, 1600, 1400, 1200],
      },
    ],
  },
}

const doughnutChartData = {
  labels: ['Renowacja butów', 'Personalizacja butów', 'Personalizacja ubrań', 'Haft ręczny', 'Haft komputerowy', 'Inne'],
  datasets: [
    {
      label: '2023',
      data: [10, 23, 15, 5, 2, 1], 
    },
    {
      label: '2024',
      data: [15, 30, 20, 10, 5, 2],
    },
  ],
}

const lineChartData = {
  labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
  datasets: [
    {
      label: '2023',
      data: [40, 39, 10, 40, 45, 80, 40, 72, 88, 105, 13, 0],
    },
    {
      label: '2024',
      data: [60, 55, 32, 10, 2, 12, 53, 23, 40, 55, 13, 100],
    },

  ],
}
</script>