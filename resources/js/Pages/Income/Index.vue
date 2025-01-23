<template>
  <ListLayout 
    :objects="incomes" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :footer="footer"
    :cards="cards"
    :get="'incomes.index'"
    :actions="Actions"
  >
    <template #title>
      Lista przychodów
    </template>
    <template #create>
      <Link
        :href="route('incomes.create')" 
        class="btn-primary"
      >
        + Dodaj przychód
      </Link>
    </template>
  </ListLayout>
</template>
    
<script setup>
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import Actions from '@/Pages/Income/Index/Components/Actions.vue'
import { provide } from 'vue'
import Box from '@/Components/UI/List/Box.vue'
  
const props = defineProps({
  incomes: Object,
  filters: Object,
  sort: Object,
  footer: Object,
  users: Array,
})
    
const columns = {
  title: { label: 'Tytuł'},
  price: { label: 'Kwota', suffix: ' zł' },
  date: { label: 'Data' },
  status: { label: 'Status', columns: ['name'] },
  remarks: { label: 'Uwagi' },
  // project: { columns: {
  //   costs: { prefix: 'Koszty stałe ', suffix: '%', label: 'Koszty' },
  //   //distribution: { admin: true, fullWidth: true, component: Box, label: 'Podział' },
  // }},
  ['project.costs']: { prefix: 'Koszty stałe ', suffix: '%', label: 'Koszty' },
  ['project.distribution']: { admin: true, fullWidth: true, component: Box, label: 'Podział' },
}

const cards = {
  title: { title: true },
  price: { suffix: ' zł' },
  status: { columns: ['name']},
  date: { },
}

const filterable = {
  search: {},
  numeric: { columns: ['price'] },
  date: { columns: ['date'] },
  dictionary: [ 
    { table: 'User', column: 'created_by_user_id', label: 'Użytkownik', admin: true },
    { table: 'IncomeStatus', column: 'status_id', label: 'Status' }, 
  ], 
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje', admin: true } ],
}

const sortable = {
  title: true,
  price: true,
  date: true,
  remarks: true,
  status: true,
  ['project.costs']: true,
}

provide('users', props.users)
  
//todo 1. wyczyścić niepotrzebne tabelki i kod z projektu, 2. utworzyć widoki show, create, edit, 3. uwzględnić, że edycja oraz usuwanie będzie dostępne tylko dla przychodów dodanych przed użytkownika, resztę uznajemy, że są powiązane z projektami
</script>