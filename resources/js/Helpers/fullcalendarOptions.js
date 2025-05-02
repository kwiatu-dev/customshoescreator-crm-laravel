import dayGridPlugin from '@fullcalendar/daygrid'
import plLocale from '@fullcalendar/core/locales/pl'

const buildDayGridOptions = (events, onEventClick, onDatesSet) => { 
  return {
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
    datesSet: onDatesSet,
  }
}

export default {
  buildDayGridOptions,
}