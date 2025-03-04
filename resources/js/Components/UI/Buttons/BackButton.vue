<template>
  <div v-if="history && history.length > 1" class="mb-4">
    <button class="btn-action" @click="back">← Cofnij</button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const history = computed(() => window.history)

const back = () => {
  window.history.back()
}

// import { router } from '@inertiajs/vue3'
// import { useNavigationHistory } from '@/Composables/useNavigationHistory'

// const navigation_history = useNavigationHistory()

// const back = () => {
//   const isUnwantedPath = (url) => {
//     const currentUrl = window.location.href
//     return url.includes('/create') || url.includes('/edit') || url === currentUrl || url + '/' === currentUrl
//   }

//   for (let i = navigation_history.value.length - 1; i >= 0; i--) {
//     const url = navigation_history.value[i]

//     if (!isUnwantedPath(url)) {
//       const steps = (navigation_history.value.length - i - 1)
//       console.log(url)
//       console.log(i)

//       window.history.go(-steps)
//       updateNavigationHistory(i, false)

//       break
//     }
//   }
// }

// const updateNavigationHistory = (retain_count, redirect) => {
//   router.put(route('navigation-history.update'), {
//     retain_count,
//     redirect,
//   })
// }
</script>