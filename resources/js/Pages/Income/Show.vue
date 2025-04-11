<template>
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-4 view-show">
    <Cards 
      :cards="card"
      :objects="[income]" 
      :actions="Actions"
    />
  </div>
</template>

<script setup>
import { provide } from 'vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Actions from '@/Pages/Income/Index/Components/Actions.vue'
import UserDistribution from '@/Components/UI/List/UserDistribution.vue'
import AdminDistribution from '@/Components/UI/List/AdminDistribution.vue'

const props = defineProps({
  income: {
    type: Object,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
})

const forIncomeCard = { 
  title: { title: true },
  date: { },
  status: { columns: ['name'] },
  price: { suffix: ' zł' },
  costs: { prefix: 'Koszty stałe: ', suffix: '%' },
  distribution: { admin: true, component: UserDistribution, fullWidth: true },
  remarks: { fullWidth: true, remarks: true },
}

const forIncomeWithRelatedProject = {
  title: { title: true, order: 1 },
  price: { suffix: ' zł', order: 2 },
  status: { columns: ['name'], order: 3 },
  date: { order: 4 },
  project: { columns: {
    costs: { prefix: 'Koszty stałe ', suffix: '%', order: 5 },
    distribution: { admin: true, fullWidth: true, component: AdminDistribution, order: 6 },
  }},
  remarks: { fullWidth: true, remarks: true, order: 7 },
}

const card = props.income?.project ? forIncomeWithRelatedProject : forIncomeCard

provide('users', props.users)
provide('disable_show_button', true)
</script>