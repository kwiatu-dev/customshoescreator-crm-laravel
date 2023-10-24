<template>
  <ListLayout 
    :objects="projects" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
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
  title: { label: 'Tytuł'},
  remarks: { label: 'Uwagi' },
  price: { label: 'Kwota', suffix: ' zł' },
  visualization: { label: 'Koszt wizualizacji', suffix: ' zł' },
  start: { label: 'Data rozpoczęcia' },
  deadline: { label: 'Deadline' },
  user: { label: 'Użytkownik', columns: ['first_name', 'last_name'], link: {column: 'user', field: 'email', prefix: 'mailto:'}},
  client: { label: 'Klient', columns: ['first_name', 'last_name'], link: {column: 'client', field: 'email', prefix: 'mailto:'} },
  status: { label: 'Status', columns: ['name'] },
  type: { label: 'Typ', columns: ['name'] },
}
  
const cards = {
  title: { title: true },
  client: { columns: ['first_name', 'last_name'], link: {column: 'client', field: 'email', prefix: 'mailto:'} },
  user: { columns: ['first_name', 'last_name'], link: {column: 'user', field: 'email', prefix: 'mailto:'} },
  price: { suffix: ' zł', concat: ['visualization'], separator: ' + ' },
  status: { columns: ['name'] },
  start: { },
  deadline: { },
}
  
const filterable = {
  search: {},
  numeric: { columns: ['price', 'visualization'] },
  date: { columns: ['start', 'deadline'] },
  dictionary: [ 
    { table: 'User', column: 'created_by_user_id', label: 'Użytkownik' },
    { table: 'ProjectStatus', column: 'status_id', label: 'Status' }, 
    { table: 'ProjectType', column: 'type_id', label: 'Typ' },
  ], 
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje' } ],
}

const sortable = { 
  title: true,
  remarks: true,
  price: true,
  visualization: true,
  start: true,
  deadline: true,
}
</script>