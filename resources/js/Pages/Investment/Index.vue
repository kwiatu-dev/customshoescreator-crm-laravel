<template>
  <ListLayout 
    :objects="investments" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :footer="footer"
    :cards="cards"
    :get="'investments.index'"
    :actions="Actions"
  >
    <template #title>
      Lista inwestycji
    </template>
    <template #create>
      <Link
        :href="route('investments.create')" 
        class="btn-primary px-4"
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
    </template>
  </ListLayout>
</template>

<script setup>
import { defineAsyncComponent } from 'vue'
import { Link } from '@inertiajs/vue3'

const ListLayout = defineAsyncComponent(() => import('@/Components/UI/List/Layout.vue'))
const Actions = defineAsyncComponent(() => import('@/Pages/Investment/Index/Components/Actions.vue'))
const Amount = defineAsyncComponent(() => import('@/Pages/Investment/Index/Components/Amount.vue'))

defineProps({
  investments: Object,
  filters: Object,
  sort: Object,
  footer: Object,
})

const columns = {
  investor: { label: 'Użytkownik (inwestor)', columns: ['first_name', 'last_name'], link: {column: 'investor', field: 'id', prefix: route('user.show', { user: '' }) + '/'}, order: 1},
  title: { label: 'Tytuł', order: 2 },
  amount: { label: 'Kwota', component: Amount, suffix: 'zł', order: 4 },
  date: { label: 'Do kiedy', order: 6 },
  status: { label: 'Status', columns: ['name'], order: 7 },
  remarks: { label: 'Uwagi', order: 8 },
}

const cards = {
  title: { title: true },
  amount: { suffix: ' zł', component: Amount },
  status: { columns: ['name']},
  investor: { label: 'Użytkownik (inwestor)', columns: ['first_name', 'last_name'], link: {column: 'investor', field: 'id', prefix: route('user.show', { user: '' }) + '/'}},
  date: { },
}

const filterable = {
  search: {},
  numeric: { columns: ['amount'] },
  date: { columns: ['date'] },
  dictionary: [ 
    { table: 'User', column: 'user_id', label: 'Inwestor', admin: true },
    { table: 'InvestmentStatus', column: 'status_id', label: 'Status' }, 
    { table: 'User', column: 'related_with_user_id', label: 'Powiązane z', admin: true },
  ], 
  pagination: {},
  others: [ 
    { name: 'deleted', label: 'Pokaż usunięte' }, 
    { name: 'created_by_user', label: 'Pokaż moje', admin: true },
    { name: 'after_date', label: 'Po czasie' }, 
  ],
}

const sortable = {
  title: true,
  amount: true,
  date: true,
  remarks: true,
  status: true,
  investor: true,
}
</script>