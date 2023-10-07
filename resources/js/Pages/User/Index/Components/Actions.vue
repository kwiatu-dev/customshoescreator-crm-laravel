<template>
  <Link :href="route('user.edit', {user: object.id})" class="btn-action">Edytuj</Link>
  <Link v-if="!object.deleted_at" :href="route('user.destroy', {user: object.id})" method="delete" as="button" class="btn-action">Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('user.restore', {user: object.id})" method="put" as="button" class="btn-action">Odzyskaj</Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  object: Object,
})
</script>