<template>
  <div class="grid grid-cols-12 gap-4">
    <Box class="col-span-6">
      <template #header>Podstawowe informacje</template>
      <div>
        Ilość zleceń oczekujących: 
      </div>
      <div>
        Ilość zleceń w trakcie realizacji:
      </div>
      <div>
        Ilość zakończonych zleceń w tym miesiącu: 
      </div>
    </Box>
    <Box class="col-span-6">
      <template #header>Powiadomienia</template>
    </Box>
    <Box class="col-span-6">
      <template #header>Rozliczenia</template>
      <div>
        Kwota oczekująca na rozliczenie: 
      </div>
      <div>
        Kwota otrzymana w tym miesiącu: 
      </div>
    </Box>
    <Box class="col-span-6">
      <template #header>Historia</template>
    </Box>
    <Box class="col-span-12">
      <template #header>Wykresy</template>
      <div class="grid grid-cols-12 gap-2">
        <div class="col-span-12">
          Wybierz rodzaj podsumowania z listy rozwijanej:
          <DropdownList v-model="selectedChart" :options="charts.map(o => o.label)" caption="Wybierz opcję" />
        </div>
        <LineChart :data="data" title="Wykres - podsumowanie dochodu" class="col-span-12" />
      </div>
    </Box>
  </div>
</template>
  
<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import Box from '@/Components/UI/List/Box.vue'
import DropdownList from '@/Components/UI/Buttons/DropdownList.vue'
import { ref } from 'vue'

const charts = [
  { label: 'Dochód', endpoint: ' ' },
  { label: 'Ilość zleceń', endpoint: ' ' },
  { label: 'Czas realizacji', endpoint: ' ' },
]

const selectedChart = ref(null)





const data = {
  labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
  datasets: [
    {
      label: '2024',
      data: [60, 55, 32, 10, 2, 12, 53, 23, 40, 55, 13, 100],
    },
    {
      label: '2023',
      data: [40, 39, 10, 40, 45, 80, 40, 72, 88, 105, 13, 0],
    },
  ],
}
</script>
  