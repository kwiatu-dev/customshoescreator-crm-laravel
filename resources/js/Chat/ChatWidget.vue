<template>
  <iframe
    ref="iframeElement"
    :src="iframeSrc"
    class="fixed bottom-4 right-4 chatbox-size"
  >
    Brak wsparcia dla iframe.
  </iframe>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, onUnmounted, ref } from 'vue'
const CHATBOT_URL = import.meta.env.VITE_CHATBOT_URL
const iframeSrc = computed(() => CHATBOT_URL)
const iframeElement = ref(null)

const REQUEST_TYPE = 'REQUEST_AUTH'
const RESPONSE_TYPE = 'AUTH_TOKEN'

onMounted(() => {
  const handler = (event) => {
    if (event.data.type === REQUEST_TYPE) {
      console.log(`Otrzymano postMessage ${REQUEST_TYPE}, przygotowywuje odpowiedź...`)
        
      import('axios').then(async ({ default: axios }) => {
        const { data } = await axios.post('/api/chat/token')

        iframeElement.value.contentWindow.postMessage(
          { type: RESPONSE_TYPE, payload: data },
          CHATBOT_URL,
        )

        console.log(`Wysłano postMessage ${RESPONSE_TYPE} do ${CHATBOT_URL}`)
      })
    }
  }

  window.addEventListener('message', handler)

  onUnmounted(() => {
    window.removeEventListener('message', handler)
  })
})
</script>

<style scoped>
.chatbox-size {
    width: 450px;
    height: 800px;
}

@media (max-width: 1024px) {
    .chatbox-size {
        width: calc(100% - 2rem);
        height: 80vh;
    }
}
</style>