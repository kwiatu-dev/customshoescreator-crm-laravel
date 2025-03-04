import { usePage } from '@inertiajs/vue3'

export const useNavigationHistory = () => {
  const { props } = usePage()
  const navigationHistory = props.navigation_history || []
  return navigationHistory
}