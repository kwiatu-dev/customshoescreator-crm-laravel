<template>
  <iframe
    ref="iframeElement"
    :src="iframeSrc"
    class="fixed bottom-4 right-4 chatbox-size"
    @load="sendUserData"
  >
    Brak wsparcia dla iframe.
  </iframe>
</template>

<script setup>
import { useAuthUser } from '@/Composables/useAuthUser'
import { computed, ref, toRaw } from 'vue'
const CHATBOT_URL = import.meta.env.VITE_CHATBOT_URL
const iframeSrc = computed(() => CHATBOT_URL)
const iframeElement = ref(null)
const user = useAuthUser()

const sendUserData = () => {
  if (iframeElement.value && iframeElement.value.contentWindow) {
    iframeElement.value.contentWindow.postMessage(
      { type: 'user_data', payload: toRaw(user.value)},
      CHATBOT_URL,
    )
  }
}
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