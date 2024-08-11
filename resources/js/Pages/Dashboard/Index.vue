<template>
  <div class="w-full bg-gray-800 rounded-md p-8 shadow-md">
    <div class="title text-center mb-8">
      Przykładowy wykres
    </div>
    <div class="flex justify-center space-x-4 mb-4">
      <button 
        class="py-2 px-4 rounded-sm shadow-md text-gray-400 font-medium"
        style="background: rgba(252, 91, 37, 0.3);"
        :class="[{ '': !data.datasets[1]?.hidden, 'line-through': data.datasets[1]?.hidden }]"
        @click="toggleDataset(1)"
      >
        2023
      </button>
      <button 
        style="background: rgba(5, 203, 225, .3);" 
        class="py-2 px-4 rounded-sm shadow-md text-gray-400 font-medium"
        :class="[{ '': !data.datasets[0]?.hidden, 'line-through': data.datasets[0]?.hidden }]"
        @click="toggleDataset(0)"
      >
        2024
      </button>
    </div>
    <div style="height: 500px;">
      <Line ref="line" :data="data" :options="options" />
    </div>
  </div>
</template>
  
<script setup>
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
} from 'chart.js'
  
import { Line } from 'vue-chartjs'
import { ref, onMounted, nextTick } from 'vue'
import _ from 'lodash'
  
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
)
  
const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      displayColors: false,
      bodyColor: 'rgb(209 213 219)',
      titleColor: 'rgb(209 213 219)',
      backgroundColor: 'rgb(17 24 39)',
      padding: 10,
      callbacks: {
        label: (context) => {
          let label = context.dataset.label || ''

          if (label) {
            label += ': '
          }
          if (context.parsed.y !== null) {
            label += new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(context.parsed.y)
          }
          return label
        },
      },
    },
  },
  scales: {
    x: {
      ticks: {
        color: 'rgb(156 163 175)',
        font: {
          size: 10,
        },
      },
    },
    y: {
      beginAtZero: true,
      ticks: {
        color: 'rgb(156 163 175)',
        font: {
          size: 10,
        },
        callback: (value, index) => {
          if (index === 0) {
            return ''
          }

          return new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(value)
        },
      },
    },
  },
}
  
const line = ref(null)
  
const data = ref({
  datasets: [],
})
  
const toggleDataset = (index) => {
  const clone = _.cloneDeep(data.value)
  clone.datasets[index].hidden = !clone.datasets[index].hidden
  data.value = clone
}
  
onMounted(async () => {
  await nextTick()
  const canvas = line.value.chart.canvas
  const ctx = canvas.getContext('2d')
  
  const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height)
  const gradient2 = ctx.createLinearGradient(0, 0, 0, canvas.height)
  
  gradient.addColorStop(0, 'rgba(247,108,6, 0.9)')
  gradient.addColorStop(0.5, 'rgba(247,108,6, 0.5)')
  gradient.addColorStop(1, 'rgba(247,108,6, 0.2)')
  
  gradient2.addColorStop(0, 'rgba(83,154,168, 0.9)')
  gradient2.addColorStop(0.5, 'rgba(83,154,168, 0.5)')
  gradient2.addColorStop(1, 'rgba(83,154,168, 0.2)')
  
  data.value = {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [
      {
        label: '2024',
        borderColor: '#05CBE1',
        pointBackgroundColor: 'rgb(209 213 219)',
        pointBorderColor: 'rgb(209 213 219)',
        borderWidth: 1,
        backgroundColor: gradient2,
        data: [60, 55, 32, 10, 2, 12, 53, 23, 40, 55, 13, 100],
        tension: 0.5,
        fill: true,
      },
      {
        label: '2023',
        borderColor: '#FC2525',
        pointBackgroundColor: 'rgb(209 213 219)',
        borderWidth: 1,
        pointBorderColor: 'rgb(209 213 219)',
        backgroundColor: gradient,
        data: [40, 39, 10, 40, 45, 80, 40, 72, 88, 105, 13, 0],
        tension: 0.5,
        fill: true,
      },
    ],
  }
})

//todo - usupełnić wykres prawdziwymi danymi, sprobówać ustawić, aby wykres budował się automatycznie (przyciski), przenieść wszystko do osobnego komponentu, dodać wyświetlanie na ekranach z trybem jasnym
</script>
  