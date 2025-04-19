import { watch } from 'vue'
import { useElementVisibility } from '@vueuse/core'

export const useElementInViewPortVisibility = (el, callback) => {
  const isVisible = useElementVisibility(el)

  watch(isVisible, async (visible) => {
    if (visible) {
      await callback()
    }
  }, { immediate: true })
}