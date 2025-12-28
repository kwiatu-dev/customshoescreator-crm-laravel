<template>
  <iframe
    ref="iframeElement"
    :src="iframeSrc"
    class="fixed bottom-6 right-4 overflow-hidden"
    style="z-index: 10; height: 800px; width: 500px;"
  >
    Brak wsparcia dla iframe.
  </iframe>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref } from 'vue'
const CHATBOT_URL = import.meta.env.VITE_CHATBOT_URL
const iframeSrc = computed(() => CHATBOT_URL)
const iframeElement = ref(null)

const REQUEST_AUTH = 'REQUEST_AUTH'
const RESPONSE_AUTH_TOKEN = 'AUTH_TOKEN'
const REDIRECT_TO_ROUTE = 'REDIRECT_TO_ROUTE'
const REDIRECT_SUCCESS = 'REDIRECT_SUCCESS'
const REDIRECT_FAILED = 'REDIRECT_FAILED'

onMounted(() => {
  const handler = (event) => {
    const { requestId } = event.data
          
    if (event.data.type === REQUEST_AUTH) {
      console.log(`Otrzymano postMessage ${REQUEST_AUTH}, przygotowywuje odpowiedź...`)
        
      import('axios').then(async ({ default: axios }) => {
        const { data } = await axios.post('/api/chat/token')

        iframeElement.value.contentWindow.postMessage(
          { type: RESPONSE_AUTH_TOKEN, payload: data },
          CHATBOT_URL,
        )

        console.log(`Wysłano postMessage ${RESPONSE_AUTH_TOKEN} do ${CHATBOT_URL}`)
      })
    }

    if (event.data.type === REDIRECT_TO_ROUTE) {
      const { routeName, params } = event.data.payload

      router.visit(route(routeName, params), {
        preserveScroll: true,
        onSuccess: async (page) => {
          iframeElement.value.contentWindow.postMessage(
            { 
              type: REDIRECT_SUCCESS, 
              payload: { url: page.url }, 
              requestId,
            }, 
            CHATBOT_URL,
          )
        },
        onError: (errors) => {
          iframeElement.value.contentWindow.postMessage({ 
            type: REDIRECT_FAILED, 
            payload: errors,
            requestId: requestId, 
          }, CHATBOT_URL)
        },
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