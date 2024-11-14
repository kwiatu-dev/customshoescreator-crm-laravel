import { computed, isRef } from 'vue'
import dayjs from 'dayjs'

export const useProjectDurationTime = (start, end) => {
  const start_ = dayjs(isRef(start) ? start.value : start)
  const end_ = dayjs(isRef(end) ? end.value : end)

  const durationDays = computed(() => end_.diff(start_, 'day'))
  const durationHours = computed(() => end_.diff(start_, 'hour'))
  const durationMonths = computed(() => end_.diff(start_, 'month'))



  return { durationDays, durationHours, durationMonths }
}