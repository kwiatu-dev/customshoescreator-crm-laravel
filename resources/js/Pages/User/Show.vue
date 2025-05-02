<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4 view-show">
    <Box class="order-1 sm:order-none">
      <template #header>Podsumowanie</template>
      <div class="grid grid-cols-1 gap-2">
        <div>
          <span class="">Rola: </span><span class="font-medium">{{ user.is_admin == true ? 'Admin' : 'Użytkownik' }}</span>
        </div>
        <div>
          <span class="">Weryfikacja emaila: </span><span class="font-medium">{{ user.email_verified_at === null ? 'BRAK' : dayjs(user.email_verified_at).format('YYYY-MM-DD') }}</span>
        </div>
        <div>
          <span class="">Data rozpoczęcia współpracy: </span><span class="font-medium">{{ dayjs(user.created_at).format('YYYY-MM-DD') }}</span>
        </div>
        <div>
          <span class="">Data zakończenia współpracy: </span><span class="font-medium">{{ user.deleted_at !== null ? dayjs(user.deleted_at).format('YYYY-MM-DD') : 'BRAK' }}</span>
        </div>
        <div>
          <span class="">Ilość zleceń oczekujących: </span><span class="font-medium">{{ user.projects.filter(project => project.status_id === 1).length }}</span>
        </div>
        <div>
          <span class="">Ilość zleceń w trakcie realizacji: </span><span class="font-medium">{{ user.projects.filter(project => project.status_id === 2).length }}</span>
        </div>
        <div>
          <span class="">Ilość zakończonych zleceń: </span><span class="font-medium">{{ user.projects.filter(project => project.status_id === 3).length }}</span>
        </div>
      </div>
    </Box>
    <Cards 
      :cards="userCard"
      :objects="[user]" 
      :actions="Actions"
    />
  </div>
</template>

<script setup>
import { defineAsyncComponent, provide } from 'vue'

const Box = defineAsyncComponent(() => import('@/Components/UI/List/Box.vue'))
const Cards = defineAsyncComponent(() => import('@/Components/UI/List/Cards.vue'))
const AdminDistribution = defineAsyncComponent(() => import('@/Components/UI/List/AdminDistribution.vue'))
const Actions = defineAsyncComponent(() => import('@/Pages/User/Index/Components/Actions.vue'))

import dayjs from 'dayjs'

const props = defineProps({
  user: Object,
  admins: Array,
})

const userCard = {
  first_name: {title: true, concat: ['last_name']},
  email: {link: {field: 'email', prefix: 'mailto:'}},
  street: {concat: ['street_nr', 'apartment_nr']},
  phone: {link: {field: 'phone', prefix: 'tel:'}},
  postcode: {concat: ['city'], separator: ', '},
  commission: { prefix: 'Prowizja: ', suffix: '%' },
  costs: { prefix: 'Koszty stałe: ', suffix: '%' },
  distribution: { admin: true, component: AdminDistribution, fullWidth: true },
}

provide('disable_show_button', true)
provide('disable_remember_state', true)
provide('users', props.admins)
</script>

<style scoped>
.line {
  margin-left: unset;
  margin-right: unset;
}
</style>