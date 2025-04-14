import { useDark } from '@vueuse/core'
import { computed } from 'vue'

const isDark = useDark()
const theme = computed(() => (isDark.value ? 'dark' : 'light'))

export const useTheme = () => {
  return theme
}

