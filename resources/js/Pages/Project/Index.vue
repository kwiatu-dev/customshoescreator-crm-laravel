<template>
  <ListLayout 
    :objects="projects" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :columns="columns" 
    :footer="footer"
    :cards="cards"
    :get="'projects.index'"
    :actions="actions"
  >
    <template #title>
      Lista projektów
    </template>
    <template #create>
      <Link
        :href="route('projects.create')" 
        class="btn-primary"
      >
        + Dodaj projekt
      </Link>
    </template>
  </ListLayout>
</template>
  
<script setup>
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import actions from '@/Pages/Project/Index/Components/Actions.vue'
  
defineProps({
  projects: Object,
  filters: Object,
  sort: Object,
  footer: Object,
})
  
const columns = {
  title: { label: 'Tytuł', 'sortable': true},
  remarks: { label: 'Uwagi', 'sortable': true },
  price: { label: 'Kwota', 'sortable': true, suffix: 'zł' },
  visualization: { label: 'Koszt wizualizacji', sortable: true, suffix: 'zł' },
  start: { label: 'Data rozpoczęcia', 'sortable': true },
  deadline: { label: 'Deadline', 'sortable': true },
  user: { label: 'Użytkownik', columns: ['first_name', 'last_name'], link: {field: 'email', prefix: 'mailto:'}},
  client: { label: 'Klient', columns: ['first_name', 'last_name'], link: {field: 'email', prefix: 'mailto:'} },
  status: { label: 'Status', columns: ['name'] },
  type: { label: 'Typ', columns: ['name'] },
}
  
const cards = {
  title: { title: true },
  remarks: {  },
  price: { suffix: 'zł' },
  visualization: { suffix: 'zł' },
  start: {  },
  deadline: {  },
  created_by_user_id: {  },
  client_id: {  },
  status_id: {  },
  type_id: {  },
}
  
const filterable = {
  search: {},
  price: {},
  visualization: {},
  date: { columns: ['start', 'deadline'] },
  pagination: {},
  others: { 
    deleted: {},
    created_by_user: {},
  },
  status: {},
  type: {},
}
</script>