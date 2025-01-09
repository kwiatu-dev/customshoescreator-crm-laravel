<template>
  <a v-if="object.file" class="btn-action" :href="route('private.files', {...object.file})" target="_blank">Pokaż fakturę</a>
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('expenses.edit', { expense: object.id })" />
  <Link v-if="!object.deleted_at" :href="route('expenses.destroy', {expense: object.id})" method="delete" as="button" class="btn-action" preserve-state preserve-scroll>Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('expenses.restore', {expense: object.id})" method="put" as="button" class="btn-action" preserve-state preserve-scroll>Odzyskaj</Link>
  <a v-if="object.file" :href="route('private.files', {...object.file})" target="_blank" class="btn-action" download>Pobierz fakturę</a>
</template>
  
<script setup>
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'
import { Link } from '@inertiajs/vue3'
  
defineProps({
  object: Object,
})
</script>