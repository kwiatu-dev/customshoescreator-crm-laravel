<template>
  <Show :investment="investment" />
  <ListLayout 
    v-if="repaymentCount > 0"
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
        v-if="investment.status_id != 2"
        :href="route('repayments.create', { investmentId: props.investment.id })" 
        class="btn-primary px-4"
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
    </template>
  </ListLayout>
  <div v-else>
    Aktualnie lista spłat jest pusta. Aby dodać pierwszą spłate, kliknij przycisk       
    <Link
      :href="route('repayments.create', { investmentId: props.investment.id })" 
      class="btn-primary px-4 ml-4"
    >
      <font-awesome-icon :icon="['fas', 'plus']" />
    </Link>
  </div>
</template>
  
<script setup>
import { provide } from 'vue'
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import Actions from '@/Pages/InvestmentRepayment/Index/Components/Actions.vue'
import Show from '@/Pages/Investment/Show.vue'
  
const props = defineProps({
  investment: Object,
  repayments: Object,
  filters: Object,
  sort: Object,
  footer: Object,
  repaymentCount: Number,
})
  
const columns = {
  repayment: { label: 'Spłata', suffix: ' zł' },
  date: { label: 'Data' },
  remarks: { label: 'Uwagi' },
}
  
const cards = {
  blank: { blank: true, title: true },
  repayment: { label: 'Spłata', prefix: 'Spłata: ', suffix: 'zł' },
  date: { label: 'Data', prefix: 'Data: ' },
  remarks: { label: 'Uwagi' },
}
  
const filterable = {
  search: {},
  numeric: { columns: ['repayment'] },
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
provide('disable_remember_state', true)
</script>