<template>
  <Link :href="route('expenses.edit', {expense: object.id})" class="btn-action">Edytuj</Link>
  <Link v-if="!object.deleted_at" :href="route('expenses.destroy', {expense: object.id})" method="delete" as="button" class="btn-action">Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('expenses.restore', {expense: object.id})" method="put" as="button" class="btn-action">Odzyskaj</Link>
  <a v-if="object.file" :href="route('private.files', {...object.file})" target="_blank" class="btn-action" download>Pobierz fakturę</a>
  <!-- <a v-if="object.file" :href="object.file" download class="btn-action" target="_blank">Pobierz fakturę</a> -->
</template>
  
<script setup>
import { Link } from '@inertiajs/vue3'
  
defineProps({
  object: Object,
})

//todo 1. dodać kategorie w wydatkach, 2. zmienić created_by_user_id na losowego użytkownika w klientach i w wydatkach, 3. dodać remember state, 4. wyświetlać odpowiednie przyciski akcji (jeżeli nie ma faktury to nie wyświetlać przycisku), 5. dodać przychód zakładkę
</script>