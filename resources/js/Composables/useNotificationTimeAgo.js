import { computed } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import 'dayjs/locale/pl'

dayjs.extend(relativeTime)
dayjs.locale('pl')

export function useNotificationTimeAgo(date) {
  const timeAgo = computed(() => {
    const inputDate = dayjs(date)
    const now = dayjs()

    const yearsDiff = now.diff(inputDate, 'year')
    if (yearsDiff >= 10) {
      return inputDate.format('D MMMM YYYY') 
    }

    return inputDate.fromNow() 
  })

  return {
    timeAgo,
  }
}