<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 view-show">
    <Cards
      :cards="card"
      :objects="[income]" 
      :actions="Actions"
    />
    <SummerizeIncome :income="income" class="md:-order-1" />
  </div>
</template>

<script setup>
import { defineAsyncComponent, provide } from 'vue'

const Cards = defineAsyncComponent(() => import('@/Components/UI/List/Cards.vue'))
const Actions = defineAsyncComponent(() => import('@/Pages/Income/Index/Components/Actions.vue'))
const UserDistribution = defineAsyncComponent(() => import('@/Components/UI/List/UserDistribution.vue'))
const AdminDistribution = defineAsyncComponent(() => import('@/Components/UI/List/AdminDistribution.vue'))
const SummerizeIncome = defineAsyncComponent(() => import('@/Pages/Income/Global/Components/SummerizeIncome.vue'))

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
  price: { prefix: 'Kwota: ', suffix: ' zł' },
  costs: { prefix: 'Koszty stałe: ', suffix: '%' },
  distribution: { admin: true, component: UserDistribution, fullWidth: true },
  remarks: { fullWidth: true, remarks: true },
}

const forIncomeWithRelatedProject = {
  title: { title: true, order: 1 },
  date: { order: 2 },
  status: { columns: ['name'], order: 3 },
  price: { prefix: 'Kwota: ', suffix: ' zł', order: 4 },


  project: { columns: {
    costs: { prefix: 'Koszty stałe ', suffix: '%', order: 5 },
    blank: { blank: true, order: 6 },
    commission: { prefix: 'Prowizja ', suffix: '%', order: 7 },
    distribution: { admin: true, fullWidth: true, component: AdminDistribution, order: 8 },
  }},
  remarks: { fullWidth: true, remarks: true, order: 9 },
}

const card = props.income?.project ? forIncomeWithRelatedProject : forIncomeCard

provide('users', props.users)
provide('disable_show_button', true)
</script>