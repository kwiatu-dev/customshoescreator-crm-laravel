<template>
  <Link :disabled="!object.editable" :href="route('user-events.edit', { user_event: object.id })" as="button" class="btn-action">Edytuj</Link>
  <Link :disabled="!object.deletable" :href="route('user-events.destroy', { user_event: object.id })" method="delete" as="button" class="btn-action" preserve-scroll preserve-state>Usuń</Link>
  <Link :disabled="!object.restorable" :href="route('user-events.restore', { user_event: object.id })" method="put" as="button" class="btn-action" preserve-scroll preserve-state>Odzyskaj</Link>
</template>
  
<script setup>
import { Link } from '@inertiajs/vue3'
  
defineProps({
  object: Object,
})

</script>
  