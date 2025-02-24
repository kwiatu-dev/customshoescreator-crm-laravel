<template>
  <Show :investment="investment" />
  <ListLayout 
    v-if="repayments?.data?.length"
    :objects="repayments" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :footer="footer"
    :cards="cards"
    :get="'repayments.index'"
    :actions="Actions"
  >
    <template #create>
      <Link
        :href="route('repayments.create')" 
        class="btn-primary px-4"
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
    </template>
  </ListLayout>
</template>
  
<script setup>
import { provide } from 'vue'
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import Actions from '@/Pages/InvestmentRepayment/Index/Components/Actions.vue'
import Show from '@/Pages/Investment/Show.vue'
  
defineProps({
  investment: Object,
  repayments: Object,
  filters: Object,
  sort: Object,
  footer: Object,
})
  
const columns = {
  repayment: { label: 'Spłata' },
  date: { label: 'Data' },
  remarks: { label: 'Uwagi' },
}
  
const cards = {
  repayment: { label: 'Spłata', prefix: 'Spłata: ', suffix: 'zł' },
  date: { label: 'Data', prefix: 'Data: ' },
  remarks: { label: 'Uwagi' },
}
  
const filterable = {
  search: {},
  date: { columns: ['date'] },
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje', admin: true } ],
}
  
const sortable = {
  repayment: true,
  date: true,
  remarks: true,
}

provide('disable_show_button', true)
</script>