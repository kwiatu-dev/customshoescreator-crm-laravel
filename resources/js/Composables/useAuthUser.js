import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export const useAuthUser = () => { 
  const page = usePage()

  const currentUser = computed(
    () => page.props.currentUser,
  )

  return currentUser
}