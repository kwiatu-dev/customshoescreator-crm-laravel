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

    <FullCalendar v-if="fullCalendarOptions" ref="fullCalendar" :options="fullCalendarOptions" />

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
import { defineAsyncComponent, ref, computed, watch, nextTick, onBeforeMount, provide } from 'vue'
import { useProjectEvent } from '@/Composables/useProjectEvent'
import { useUserEvent } from '@/Composables/useUserEvent'
import { useQueryParams } from '@/Composables/useQueryParams'
import { router } from '@inertiajs/vue3'

const Filters = defineAsyncComponent(() => import('@/Components/UI/List/Filters.vue'))
const FullCalendar = defineAsyncComponent(() => import('@fullcalendar/vue3'))
const FormPopup = defineAsyncComponent(() => import('@/Components/UI/Popup/FormPopup.vue'))
const FormUserEventCreate = defineAsyncComponent(() => import('@/Pages/UserEvents/Create.vue'))


import dayjs from 'dayjs'
 
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
const fullCalendarOptions = ref(null)
const projectEvents = computed(() => props.projects.map(useProjectEvent))
const userEvents = computed(() => props.userEvents.map(useUserEvent))
const allEvents = computed(() => [...projectEvents.value, ...userEvents.value])

onBeforeMount(async () => {
  const { default: fullCalendarOptionsHelper } = await import('@/Helpers/fullcalendarOptions.js')

  const onEventClick = (info) => { 
    const event = info.event
    const link = event.extendedProps.link
        
    if (link) {
      router.post(route('remember.state'), {
        url: link,
        params: useQueryParams(),
      })
    }
  }
        
  const onDatesSet = () => {
    addFullCalendarViewDateToQueryParams()
  }

  fullCalendarOptions.value = fullCalendarOptionsHelper.buildDayGridOptions(allEvents, onEventClick, onDatesSet)

  const query = useQueryParams()
  const date = query.date || dayjs().format('YYYY-MM-DD')
  fullCalendarOptions.value.initialDate = date
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

const addFullCalendarViewDateToQueryParams = () => {
  const query = useQueryParams()

  if (!fullCalendar.value) {
    return
  }

  const calendarApi = fullCalendar.value.getApi()
  const currentDate = calendarApi.getCurrentData().currentDate
  const formattedDate = dayjs(currentDate).format('YYYY-MM-DD')
  const newUrl = route('organizer.index', { ...query, date: formattedDate }) 

  if (query.date !== formattedDate) {
    router.get(newUrl, {}, { replace: true, preserveScroll: true, preserveState: true }) 
  } 
}

const reloadFullCalendar = async () => {
  if (!fullCalendar.value) {
    return
  }

  await nextTick()
  const calendarApi = fullCalendar.value.getApi()
  calendarApi.removeAllEvents()
  calendarApi.addEventSource(allEvents.value)
}

provide('users', props.users)
provide('types', props.types)

watch(allEvents, (_) => reloadFullCalendar())
</script>

<style>
.organizer td {
 overflow: unset !important;
}

.fc-toolbar-chunk {
  @apply flex flex-row flex-nowrap gap-4 mt-2;
}

.fc-next-button, .fc-prev-button {
  @apply !bg-gray-300 dark:!bg-gray-800 !py-2 !px-4 !border-0 hover:!bg-gray-200 dark:hover:!bg-gray-700 !m-0 !text-gray-600 dark:!text-gray-200; 
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

.fc .fc-event {
  margin-bottom: 1px !important;
}
</style>
