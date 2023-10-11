<template>
  <ListLayout 
    :objects="expenses" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
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
        class="btn-primary w-1/3 rounded-l-none h-11 text-center md:rounded-l-md md:w-auto md:text-left"
      >
        + Dodaj <span class="hidden md:inline">wydatek</span>
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
  title: { label: 'Tytuł', 'sortable': true},
  price: { label: 'Kwota', 'sortable': true, suffix: 'zł' },
  date: { label: 'Data', 'sortable': true },
  shop_name: { label: 'Nazwa sklepu', 'sortable': true },
}

const cards = {
  title: { title: true },
  date: { },
  shop_name: { },
  price: { suffix: 'zł' },
}

const filterable = {
  search: {},
  price: {},
  date: {},
  others: { 
    deleted: {},
  },
}
</script>