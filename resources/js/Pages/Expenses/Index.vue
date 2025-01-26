<template>
  <ListLayout 
    :objects="expenses" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :footer="footer"
    :cards="cards"
    :get="'expenses.index'"
    :actions="actions"
  >
    <template #title>
      Lista wydatków
    </template>
    <template #create>
      <Link
        :href="route('expenses.create')" 
        class="btn-primary px-4"
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
    </template>
  </ListLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import actions from '@/Pages/Expenses/Index/Components/Actions.vue'

defineProps({
  expenses: Object,
  filters: Object,
  sort: Object,
  footer: Object,
})

const columns = {
  title: { label: 'Tytuł'},
  price: { label: 'Kwota', suffix: ' zł' },
  date: { label: 'Data' },
  shop_name: { label: 'Nazwa sklepu' },
}

const cards = {
  title: { title: true },
  date: { },
  shop_name: { },
  price: { suffix: 'zł' },
}

const filterable = {
  search: {},
  numeric: { columns: ['price'] },
  date: { columns: ['date'] },
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje' } ],
}

const sortable = {
  title: true,
  price: true,
  date: true,
  shop_name: true,
}
</script>