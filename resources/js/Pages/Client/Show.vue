<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <Box>
      <template #header>Podsumowanie</template>
      <div class="grid grid-cols-1 gap-2">
        <div>
          <span class="">Data utworzenia: </span><span class="font-medium">{{ dayjs(client.created_at).format('YYYY-MM-DD') }}</span>
        </div>
        <div>
          <span class="">Data usunięcia: </span><span class="font-medium">{{ client.deleted_at !== null ? dayjs(client.deleted_at).format('YYYY-MM-DD') : 'BRAK' }}</span>
        </div>
        <div>
          <span class="">Ilość zleceń oczekujących: </span><span class="font-medium">{{ client.projects.filter(project => project.status_id === 1).length }}</span>
        </div>
        <div>
          <span class="">Ilość zleceń w trakcie realizacji: </span><span class="font-medium">{{ client.projects.filter(project => project.status_id === 2).length }}</span>
        </div>
        <div>
          <span class="">Ilość zakończonych zleceń: </span><span class="font-medium">{{ client.projects.filter(project => project.status_id === 3).length }}</span>
        </div>
      </div>
    </Box>
    <Cards 
      :cards="clientCard"
      :objects="[client]" 
      :actions="Actions"
    />
  </div>
  <div v-if="projects.length" class="mb-4">
    <div class="text-gray-500 font-medium mb-1">Projekty</div>
    <div class="grid md:grid-cols-2 grid-cols-1 gap-2">
      <Cards 
        :cards="projectCards"
        :objects="projects" 
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import dayjs from 'dayjs'
import Box from '@/Components/UI/List/Box.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Actions from '@/Pages/Client/Index/Components/Actions.vue'
import { provide } from 'vue'

defineProps({
  client: {
    type: Object,
    required: true,
  },
  projects: {
    type: Array,
    required: true,
  },
})

const clientCard = {
  first_name: { title: true, concat: ['last_name'] },
  email: { link: { field: 'email', prefix: 'mailto:' }},
  street: { concat: ['street_nr', 'apartment_nr'] },
  phone: { link: { field: 'phone', prefix: 'tel:' }},
  postcode: { concat: ['city'], separator: ', ' },
}

const projectCards = {
  title: { title: true },
  client: { columns: ['first_name', 'last_name'], link: {column: 'client', field: 'email', prefix: 'mailto:'} },
  user: { columns: ['first_name', 'last_name'], link: {column: 'user', field: 'email', prefix: 'mailto:'} },
  price: { suffix: ' zł', concat: ['visualization'], separator: ' + ' },
  status: { columns: ['name'] },
  start: { },
  deadline: { },
  end: {},
}

provide('disable_show_button', true)
provide('disable_remember_state', true)
</script>