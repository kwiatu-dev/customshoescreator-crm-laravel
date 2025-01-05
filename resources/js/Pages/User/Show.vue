<template>
  <div class="mb-4">
    <RestoreStateButton class="underline" :url="route('user.index')" label="← Cofnij" />
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <Box>
      <template #header>Podsumowanie</template>
      <div class="grid grid-cols-1 gap-2">
        <div>
          <span class="">Rola: </span><span class="font-medium">{{ user.is_admin == true ? 'Admin' : 'Użytkownik' }}</span>
        </div>
        <div>
          <span class="">Weryfikacja emaila: </span><span class="font-medium">{{ user.email_verified_at === null ? 'BRAK' : user.email_verified_at }}</span>
        </div>
        <div>
          <span class="">Data rozpoczęcia współpracy: </span><span class="font-medium">{{ dayjs(user.created_at).format('YYYY-MM-DD') }}</span>
        </div>
        <div>
          <span class="">Data zakończenia współpracy: </span><span class="font-medium">{{ user.deleted_at !== null ? dayjs(user.deleted_at).format('YYYY-MM-DD') : 'BRAK' }}</span>
        </div>
        <div>
          <span class="">Ilość zleceń oczekujących: </span><span class="font-medium">{{ user.projects.filter(project => project.status_id === 1).length }}</span>
        </div>
        <div>
          <span class="">Ilość zleceń w trakcie realizacji: </span><span class="font-medium">{{ user.projects.filter(project => project.status_id === 2).length }}</span>
        </div>
        <div>
          <span class="">Ilość zakończonych zleceń: </span><span class="font-medium">{{ user.projects.filter(project => project.status_id === 3).length }}</span>
        </div>
      </div>
    </Box>
    <Cards 
      :cards="userCard"
      :objects="[user]" 
      :actions="Actions"
    />
  </div>
  <div class="mb-4">
    <Box>
      <template #header>Statystyki</template>
      <div class="grid grid-cols-12 gap-2">
        <div class="col-span-12">
          Wybierz rodzaj podsumowania z listy rozwijanej:
          <DropdownList v-model="selectedChart" :options="charts.map(o => o.label)" caption="Wybierz opcję" />
        </div>
        <LineChart :data="data" title="Wykres - podsumowanie dochodu" class="col-span-12" />
      </div>
    </Box>
  </div>
  <div v-if="user.projects.length">
    <div class="text-gray-500 font-medium mb-1">Zlecenia</div>
    <div class="grid md:grid-cols-2 grid-cols-1 gap-2">
      <Cards 
        :cards="projectCards"
        :objects="user.projects" 
      />
    </div>
  </div>
</template>

<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import DropdownList from '@/Components/UI/Buttons/DropdownList.vue'
import RestoreStateButton from '@/Components/UI/Buttons/RestoreStateButton.vue'
import Box from '@/Components/UI/List/Box.vue'
import Actions from '@/Pages/User/Index/Components/Actions.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import AdminDistribution from '@/Components/UI/List/AdminDistribution.vue'
import dayjs from 'dayjs'
import { provide, ref } from 'vue'


const props = defineProps({
  user: Object,
  admins: Array,
})

const userCard = {
  first_name: {title: true, concat: ['last_name']},
  email: {link: {field: 'email', prefix: 'mailto:'}},
  street: {concat: ['street_nr', 'apartment_nr']},
  phone: {link: {field: 'phone', prefix: 'tel:'}},
  postcode: {concat: ['city'], separator: ', '},
  commission: { prefix: 'Prowizja: ', suffix: '%' },
  costs: { prefix: 'Koszty stałe: ', suffix: '%' },
  distribution: { admin: true, component: AdminDistribution, fullWidth: true },
}

const projectCards = {
  title: { title: true },
  client: { columns: ['first_name', 'last_name'], link: {column: 'client', field: 'email', prefix: 'mailto:'} },
  user: { columns: ['first_name', 'last_name'], link: {column: 'user', field: 'email', prefix: 'mailto:'} },
  price: { suffix: ' zł', concat: ['visualization'], separator: ' + ' },
  status: { columns: ['name'] },
  start: { },
  deadline: { },
  end: {},
}

provide('disable_show_button', true)
provide('disable_remember_state', true)
provide('users', props.admins)

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

//todo 
//1. Do dokończenia wykresy po skończeniu wszystkich zakładek
</script>

<style scoped>
.line {
  margin-left: unset;
  margin-right: unset;
}
</style>