<template>
  <div class="organizer">
    <h1 class="title mb-8">
      Organizator pracy
    </h1>
    <FullCalendar ref="fullCalendar" :options="calendarOptions" />
    <div 
      v-if="currentUser?.is_admin" 
      class="flex sm:flex-row sm:flex-nowrap sm:items-center sm:gap-4 mt-4 flex-col items-center"
    >
      <span>Kalendarz użytkownika: </span>
      <DropdownList 
        v-model="userSelected" 
        caption="Wybierz użytkownika z listy"
        :label="(option) => `${option.first_name} ${option.last_name}`" 
        :options="users"
        position="top"
      />
    </div>
  </div>
</template>

<script setup> 
import DropdownList from '@/Components/UI/Buttons/DropdownList.vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import plLocale from '@fullcalendar/core/locales/pl'
import axios from 'axios'
import { ref, watch } from 'vue'
import { debounce } from 'lodash'
import NProgress from 'nprogress'
 
const props = defineProps({
  currentUser: {
    type: Object,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
  projects: {
    type: Array,
    required: true,
  },
})

const userSelected = ref(props.users.find(user => user.id == props.currentUser.id) ?? null)
const projectEvents = ref(props.projects)
const fullCalendar = ref(null)

const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  locale: plLocale,
  height: 700,
  events: projectEvents,
  timeZone: 'local',
  headerToolbar: {
    left: 'prev next',
    center: '', 
    right: 'title',
  },
})

const handleUserSelect = (user) => {
  if (user)
    getUserProjects(user)
  else 
    projectEvents.value = []
}

const getUserProjects = (user) => {
  NProgress.start()

  axios.get(route('user.projects', { user: user.id })) 
    .then(response => {
      projectEvents.value = response.data
    })
    .catch(error => {
      console.error('Błąd przy pobieraniu projektów:', error)
    })
    .finally(() => {
      NProgress.done()
    })
}

watch(userSelected, debounce((user) => handleUserSelect(user), 500))
</script>

<style>
.organizer td {
 overflow: unset !important;
}

.fc-next-button, .fc-prev-button {
  @apply !bg-gray-800 !p-1 !border-0 hover:!bg-gray-700; 
}

.fc-header-toolbar {
  @apply !mb-4;
}
</style>
