<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 view-show view-show">
    <Box class="order-1 sm:order-none">
      <div class="flex flex-col gap-4">
        <div>
          <div>Inspiracje</div>
          <PhotoGrid :photos="project.images.filter(image => image.type_id === 4)" />
        </div>
        <div>
          <div>Wizualizacje komputerowe</div>
          <PhotoGrid :photos="project.images.filter(image => image.type_id === 1)" />
        </div>
        <div>
          <div>Proces realizacji</div>
          <PhotoGrid :photos="project.images.filter(image => image.type_id === 2)" />
        </div>
        <div>
          <div>Zdjęcia końcowe</div>
          <PhotoGrid :photos="project.images.filter(image => image.type_id === 3)" />
        </div>
      </div>
    </Box>
    <Cards 
      :cards="cards"
      :objects="[project]" 
      :actions="Actions"
    />
  </div>
</template>

<script setup>
import { defineAsyncComponent, provide } from 'vue'

const Box = defineAsyncComponent(() => import('@/Components/UI/List/Box.vue'))
const Cards = defineAsyncComponent(() => import('@/Components/UI/List/Cards.vue'))
const PhotoGrid = defineAsyncComponent(() => import('@/Pages/Project/Show/Components/PhotoGrid.vue'))
const AdminDistribution = defineAsyncComponent(() => import('@/Components/UI/List/AdminDistribution.vue'))
const Actions = defineAsyncComponent(() => import('@/Pages/Project/Index/Components/Actions/AllActions.vue'))

const props = defineProps({
  project: Object,
  users: Array,
})

const cards = {
  title: { title: true },
  client: { label: 'Klient', columns: ['first_name', 'last_name'], link: {column: 'client', field: 'id', prefix: route('client.show', { client: '' }) + '/'} },
  user: { label: 'Użytkownik', columns: ['first_name', 'last_name'], link: {column: 'user', field: 'id', prefix: route('user.show', { user: '' }) + '/'}},
  price: { suffix: ' zł', concat: ['visualization'], separator: ' + ' },
  status: { columns: ['name'] },
  blank: { blank: true },
  end: { prefix: 'Z: '},
  start: { prefix: 'PS: ' },
  deadline: { prefix: 'PZ: ' },
  commission: { prefix: 'Prowizja: ', suffix: '%' },
  costs: { prefix: 'Koszty stałe: ', suffix: '%' },
  distribution: { admin: true, component: AdminDistribution, fullWidth: true },
  remarks: { fullWidth: true, remarks: true },
}

provide('disable_show_button', true)
provide('disable_remember_state', true)
provide('users', props.users)
</script>