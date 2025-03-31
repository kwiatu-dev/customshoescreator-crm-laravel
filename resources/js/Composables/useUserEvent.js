import dayjs from 'dayjs'

export const useUserEvent = (userEvent) => {
  let color = null

  if (userEvent.type_id === 1) {
    color = '#7ccf00'
  }
  else {
    color = '#50a2ff'
  }

  return {
    id: userEvent.id,
    title: userEvent.title,
    start: userEvent.start,
    end: dayjs(userEvent.end).add(1, 'day').format('YYYY-MM-DD'),
    link: route('user-events.show', { user_event: userEvent.id }),
    description: userEvent.remarks,
    allDay: true,
    color,
    deleted: userEvent.deleted_at || false,
  }
}
  