import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'


export const useNavigationHistory = () => {
  const page = usePage()
  return computed(() => page.props.navigation.history)
}