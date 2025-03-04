<template>
  <div v-if="history && history.length > 1" class="mb-4">
    <button class="btn-action" @click="back">← Cofnij</button>
  </div>
</template>

<script setup>
import { useNavigationHistory } from '@/Composables/useNavigationHistory'
import { computed } from 'vue'

const navigation_history = useNavigationHistory()
const history = computed(() => window.history)

const back = () => {
  const isUnwantedPath = (url) => {
    const currentUrl = window.location.href
    return url.includes('/create') || url.includes('/edit') || url.includes(currentUrl)
  }

  for (let i = navigation_history.length - 1; i >= 0; i--) {
    const url = navigation_history[i]

    if (!isUnwantedPath(url)) {
      window.history.go(-(navigation_history.length - i - 1))
      break
    }
  }
}
</script>