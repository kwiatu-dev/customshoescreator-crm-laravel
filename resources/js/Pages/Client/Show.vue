<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4 view-show">
    <Box class="order-1 sm:order-none">
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
</template>

<script setup>
import { defineAsyncComponent, provide } from 'vue'

const Box = defineAsyncComponent(() => import('@/Components/UI/List/Box.vue'))
const Cards = defineAsyncComponent(() => import('@/Components/UI/List/Cards.vue'))
const Actions = defineAsyncComponent(() => import('@/Pages/Client/Index/Components/Actions.vue'))

import dayjs from 'dayjs'

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

provide('disable_show_button', true)
provide('disable_remember_state', true)
</script>