<template>
  <BackButton />
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-4">
    <Cards 
      :cards="card"
      :objects="[income]" 
      :actions="Actions"
    />
  </div>
</template>

<script setup>
import { provide } from 'vue'
import BackButton from '@/Components/UI/Buttons/BackButton.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Actions from '@/Pages/Income/Index/Components/Actions.vue'
import UserDistribution from '@/Components/UI/List/UserDistribution.vue'

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
  status: { columns: ['name']},
  price: { suffix: ' zł' },
  costs: { prefix: 'Koszty stałe: ', suffix: '%' },
  distribution: { admin: true, component: UserDistribution, fullWidth: true },
  remarks: { fullWidth: true, remarks: true },
}

const forIncomeWithRelatedProject = {
  title: { title: true },
  price: { suffix: ' zł' },
  status: { columns: ['name']},
  date: { },
  //project: { columns: ['costs'], prefix: 'Koszty stałe: ', suffix: '%' },
  project: { columns: ['distribution'], admin: true, fullWidth: true },
  remarks: { fullWidth: true, remarks: true },
}

//todo: umożliwić wprowadzanie pól elementów relacyjnych więcej niż jednego, jeżeli wybieramy element relacyjny to przekazujmy ten obiekt do komponentu

const card = props.income?.project ? forIncomeWithRelatedProject : forIncomeCard

provide('users', props.users)
provide('disable_show_button', true)
</script>