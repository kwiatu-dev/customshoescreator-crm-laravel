<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <Box>
      <div class="flex flex-col gap-4">
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
        <div>
          <div>Inspiracje</div>
          <PhotoGrid :photos="project.images.filter(image => image.type_id === 4)" />
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
import Box from '@/Components/UI/List/Box.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Actions from '@/Pages/Project/Index/Components/Actions/AllActions.vue'
import PhotoGrid from '@/Pages/Project/Show/Components/PhotoGrid.vue'
import AdminDistribution from '@/Components/UI/List/AdminDistribution.vue'
import { provide } from 'vue'

defineProps({
  project: Object,
})

const cards = {
  title: { title: true },
  client: { columns: ['first_name', 'last_name'], link: {column: 'client', field: 'email', prefix: 'mailto:'} },
  user: { columns: ['first_name', 'last_name'], link: {column: 'user', field: 'email', prefix: 'mailto:'} },
  price: { suffix: ' zł', concat: ['visualization'], separator: ' + ' },
  status: { columns: ['name'] },
  start: { },
  deadline: { },
  commission: { suffix: '%' },
  costs: { suffix: '%' },
  distribution: { admin: true, component: AdminDistribution },
  remarks: { remarks: true },
}

provide('disable_show_button', true)
</script>