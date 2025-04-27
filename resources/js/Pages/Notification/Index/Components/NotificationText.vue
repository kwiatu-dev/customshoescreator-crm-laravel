<template>
  <div v-if="notification.data.message">
    <span v-for="(part, index) in parsedMessage" :key="index">
      <Link v-if="part.type === 'link'" :href="part.url" class="underline text-indigo-500">{{ part.text }}</Link>
      <span v-else>{{ part.text }}</span>
    </span>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  notification: {
    type: Object,
    required: true,
  },
})

function parseMessage(message) {
  const regex = /link\[(.+?)\]\((.+?)\)/g
  const parts = []
  let lastIndex = 0
  let match

  while ((match = regex.exec(message)) !== null) {
    if (match.index > lastIndex) {
      parts.push({ type: 'text', text: message.slice(lastIndex, match.index) })
    }

    parts.push({ type: 'link', url: match[1], text: match[2] })
    lastIndex = regex.lastIndex
  }

  if (lastIndex < message.length) {
    parts.push({ type: 'text', text: message.slice(lastIndex) })
  }

  return parts
}

const parsedMessage = computed(() => parseMessage(props.notification.data.message))
</script>