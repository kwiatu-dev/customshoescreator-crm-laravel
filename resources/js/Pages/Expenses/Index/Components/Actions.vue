<template>
  <a :disabled="object.file === null" class="btn-action" :href="route('private.files', {...object.file})" target="_blank">Pokaż fakturę</a>
  <a :disabled="object.file === null" :href="route('private.files', {...object.file})" target="_blank" class="btn-action" download>Pobierz fakturę</a>
  <RememberStateButton :disabled="!object.editable" label="Edytuj" :url="route('expenses.edit', { expense: object.id })" />
  <Link :disabled="!object.deletable" :href="route('expenses.destroy', {expense: object.id})" method="delete" as="button" class="btn-action" preserve-state preserve-scroll>Usuń</Link>
  <Link :disabled="!object.restorable" :href="route('expenses.restore', {expense: object.id})" method="put" as="button" class="btn-action" preserve-state preserve-scroll>Odzyskaj</Link>
</template>
  
<script setup>
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'
import { Link } from '@inertiajs/vue3'
  
defineProps({
  object: Object,
})
</script>