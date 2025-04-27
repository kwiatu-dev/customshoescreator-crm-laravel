<template>
  <div v-if="notification.type === 'App\\Notifications\\ProjectCreate'">
    Nowe zlecenie 
    <Link 
      :href="notification.data.project_url"
      class="underline text-indigo-500 hover:text-indigo-400"
    >
      {{ notification.data.project_title }}
    </Link> 
    zostało do Ciebie przypisane.
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  notification: {
    type: Object,
    required: true,
  },
})
</script>