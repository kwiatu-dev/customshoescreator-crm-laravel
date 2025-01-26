<template>
  <ListLayout 
    :styles="styles"
    :objects="projects" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :footer="footer"
    :cards="cards"
    :get="'projects.index'"
    :actions="Actions"
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
import Actions from '@/Pages/Project/Index/Components/Actions/AllActions.vue'
import dayjs from 'dayjs'

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
  end: { label: 'Data zakończenia' },
  user: { label: 'Użytkownik', columns: ['first_name', 'last_name'], link: {column: 'user', field: 'id', prefix: route('user.show', { user: '' }) + '/'}},
  client: { label: 'Klient', columns: ['first_name', 'last_name'], link: {column: 'client', field: 'id', prefix: route('client.show', { client: '' }) + '/'} },
  status: { label: 'Status', columns: ['name'] },
  type: { label: 'Typ', columns: ['name'] },
}
  
const cards = {
  title: { title: true },
  client: { label: 'Klient', columns: ['first_name', 'last_name'], link: {column: 'client', field: 'id', prefix: route('client.show', { client: '' }) + '/'} },
  user: { label: 'Użytkownik', columns: ['first_name', 'last_name'], link: {column: 'user', field: 'id', prefix: route('user.show', { user: '' }) + '/'}},
  price: { suffix: ' zł', concat: ['visualization'], separator: ' + ' },
  status: { columns: ['name'] },
  start: { },
  deadline: { },
  end: {},
}
  
const filterable = {
  search: {},
  numeric: { columns: ['price', 'visualization'] },
  date: { columns: ['start', 'deadline', 'end'] },
  dictionary: [ 
    { table: 'User', column: 'created_by_user_id', label: 'Użytkownik', admin: true },
    { table: 'ProjectStatus', column: 'status_id', label: 'Status' }, 
    { table: 'ProjectType', column: 'type_id', label: 'Typ' },
  ], 
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje', admin: true }],
}

const sortable = { 
  title: true,
  remarks: true,
  price: true,
  visualization: true,
  start: true,
  deadline: true,
  end: true,
}

const styles = {
  row: (object) => {
    const now = dayjs()
    const deadline = dayjs(object.deadline)
    const daysUntilDeadline = deadline.diff(now, 'day')

    if (object.deleted_at !== null) {
      return {}
    }

    if (object.status_id === 3) {
      return { backgroundColor: 'rgba(34, 139, 34, .2)' } 
    }

    if (daysUntilDeadline <= 14) {
      return {
        backgroundColor: 'rgba(250, 20, 0, .2)', 
        animation: 'pulse-row 1.5s ease-in-out infinite',
      }
    }

    if (daysUntilDeadline <= 62) {
      const colorIntensity = Math.floor((62 - daysUntilDeadline) / 48 * 255)

      return {
        backgroundColor: `rgba(255, ${Math.max(255 - colorIntensity, 0)}, 0, .2)`, 
      }
    }

    return {}
  },
}
</script>

<style>
@keyframes pulse-row {
  0% {
    background-color: rgba(250, 20, 0, 0.2); 
  }
  50% {
    background-color: rgba(250, 20, 0, 0.3); 
  }
  100% {
    background-color: rgba(250, 20, 0, 0.2);
  }
}
</style>