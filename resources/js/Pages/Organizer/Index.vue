<template>
  <div class="organizer">
    <h1 class="title mb-8">
      Organizator pracy
    </h1>
    <div class="flex flex-row gap-4">
      <Link
        :href="route('organizer.create')" 
        class="btn-primary px-4" 
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
      <Filters 
        ref="filtersElement"
        :filters="filters" 
        :filterable="filterable" 
        :sort="[]" 
        :get="route('organizer.index')" 
        :columns="columns" 
      />
    </div>

    <FullCalendar ref="fullCalendar" :options="calendarOptions" />
  </div>
</template>

<script setup> 
import Filters from '@/Components/UI/List/Filters.vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import plLocale from '@fullcalendar/core/locales/pl'
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
 
const props = defineProps({
  projects: {
    type: Array,
    required: true,
  },
  userEvents: {
    type: Array,
    required: true,
  },
  filters: Object,
})

const fullCalendar = ref(null)

const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  locale: plLocale,
  height: 700,
  events: [],
  timeZone: 'local',
  headerToolbar: {
    left: 'prev next',
    center: '', 
    right: 'title',
  },
})

const filterable = {
  search: {},
  date: { columns: ['start', 'end'] },
  dictionary: [ 
    { table: 'User', column: 'user_id', label: 'Użytkownik', admin: true },
    { table: 'UserEventType', column: 'type_id', label: 'Typ wydarzenia' }, 
  ], 
  list: [
    { 
      label: 'Typ danych', 
      column: 'category',
      data: [
        { name: 'Projekty', value: 'projects' },
        { name: 'Wydarzenia', value: 'events' },
      ], 
    },
  ],
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje', admin: true } ],
}

const columns = {
  start: { label: 'Data rozpoczęcia' },
  end: { label: 'Data zakończenia' },
}
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
