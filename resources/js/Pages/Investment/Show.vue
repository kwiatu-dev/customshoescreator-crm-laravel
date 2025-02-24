<template>
  <BackButton />
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-4">
    <Cards 
      :cards="card"
      :objects="[investment]" 
      :actions="Actions"
    />
  </div>
</template>

<script setup>
import BackButton from '@/Components/UI/Buttons/BackButton.vue'
import Actions from '@/Pages/Investment/Index/Components/Actions.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Amount from '@/Pages/Investment/Index/Components/Amount.vue'

defineProps({
  investment: {
    type: Object,
    required: true,
  },
})

const card = {
  title: { title: true },
  amount: { suffix: ' zł', component: Amount },
  status: { columns: ['name']},
  investor: { label: 'Użytkownik (inwestor)', columns: ['first_name', 'last_name'], link: {column: 'investor', field: 'id', prefix: route('user.show', { user: '' }) + '/'}},
  date: { },
  remarks: { remarks: true },
}
</script>