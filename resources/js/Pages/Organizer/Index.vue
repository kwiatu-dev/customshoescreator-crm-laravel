<template>
  <div class="organizer">
    <h1 class="title mb-8">
      Organizator pracy
    </h1>
    <div class="flex flex-row gap-4">
      <FormPopup :form="FormUserEventCreate" @form-action-created="onNewUserEventCreated">
        <button
          class="btn-primary px-4" 
        >
          <font-awesome-icon :icon="['fas', 'plus']" />
        </button>
      </FormPopup>
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

    <div class="flex flex-nowrap flex-row gap-4 items-center mt-4">
      <div>
        <span class="w-2 h-2 inline-block bg-indigo-500 rounded-full mr-2" />
        <span>Projekt</span>
      </div>
      <div>
        <span class="w-2 h-2 inline-block bg-lime-500 rounded-full mr-2" />
        <span>Dni wolne</span>
      </div>
      <div>
        <span class="w-2 h-2 inline-block bg-blue-500 rounded-full mr-2" />
        <span>Inne wydarzenie</span>
      </div>
    </div>
  </div>
</template>

<script setup> 
import Filters from '@/Components/UI/List/Filters.vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import plLocale from '@fullcalendar/core/locales/pl'
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useProjectEvent } from '@/Composables/useProjectEvent'
import { useUserEvent } from '@/Composables/useUserEvent'
import FormPopup from '@/Components/UI/Popup/FormPopup.vue'
import FormUserEventCreate from '@/Pages/UserEvents/Create.vue'
import { provide } from 'vue'
import { router } from '@inertiajs/vue3'
 
const props = defineProps({
  projects: {
    type: Array,
    required: true,
  },
  userEvents: {
    type: Array,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
  types: {
    type: Array,
    required: true,
  },
  filters: Object,
})

const fullCalendar = ref(null)
const projectEvents = computed(() => props.projects.map(useProjectEvent))
const userEvents = computed(() => props.userEvents.map(useUserEvent))
const events = computed(() => [...projectEvents.value, ...userEvents.value])

const reload = async () => {
  if (fullCalendar.value) {
    await nextTick()
    const calendarApi = fullCalendar.value.getApi()
    calendarApi.removeAllEvents()
    calendarApi.addEventSource(events.value)
  }
}

const onEventClick = (info) => { 
  const event = info.event
  const link = event.extendedProps.link

  if (link) {
    router.visit(link)
  }
}

const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  locale: plLocale,
  height: 700,
  events: events,
  timeZone: 'local',
  headerToolbar: {
    left: 'prev next',
    center: '', 
    right: 'title',
  },
  eventClassNames: (object) => {
    if (object.event.extendedProps.deleted) {
      return ['event-deleted']
    }
  },
  eventClick: onEventClick,
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

const onNewUserEventCreated = (userEvent) => {
  
}

provide('users', props.users)
provide('types', props.types)

watch(events, (_) => reload())
</script>

<style>
.organizer td {
 overflow: unset !important;
}

.fc-toolbar-chunk {
  @apply flex flex-row flex-nowrap gap-4 mt-2;
}

.fc-next-button, .fc-prev-button {
  @apply !bg-gray-800 !py-2 !px-4 !border-0 hover:!bg-gray-700 !m-0; 
  width: 46px;
  height: 40px;
}

.fc-prev-button {
  width: 48px;
}

.fc-next-button .fc-icon, .fc-prev-button .fc-icon {
  @apply flex flex-row justify-center items-center;
  width: 13px;
  height: 13px;
}

.fc-header-toolbar {
  @apply !mb-4;
}

.event-deleted {
  @apply !border-2 !border-dashed !border-rose-600;
}

.event-deleted .fc-event-title{
  text-decoration: line-through;
}

.fc-event {
  @apply cursor-pointer;
}
</style>
