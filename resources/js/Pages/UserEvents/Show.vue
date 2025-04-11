<template>
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-4 view-show">
    <Cards 
      :cards="card"
      :objects="[userEvent]" 
      :actions="Actions"
    />
  </div>
</template>

<script setup>
import Cards from '@/Components/UI/List/Cards.vue'
import Actions from '@/Pages/UserEvents/Components/Actions.vue'

defineProps({
  userEvent: {
    type: Object,
    required: true,
  },
})

const card = { 
  title: { title: true },
  type: { columns: ['name'], prefix: 'Typ: ' },
  user: { label: 'Użytkownik', columns: ['first_name', 'last_name'], link: {column: 'user', field: 'id', prefix: route('user.show', { user: '' }) + '/'}},
  start: { prefix: 'Rozpoczęcie: ' },
  end: { prefix: 'Zakończenie: ' },
  remarks: { fullWidth: true, remarks: true },
}
</script>