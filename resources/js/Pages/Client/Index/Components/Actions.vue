<template>
  <Link v-if="!disableShowButton" :href="route('client.show', { client: object.id })" class="btn-action">Zobacz</Link>
  <Link :href="route('client.edit', {client: object.id})" class="btn-action">Edytuj</Link>
  <Link v-if="!object.deleted_at" :href="route('client.destroy', {client: object.id})" method="delete" as="button" class="btn-action">Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('client.restore', {client: object.id})" method="put" as="button" class="btn-action">Odzyskaj</Link>
</template>
    
<script setup>
import { Link } from '@inertiajs/vue3'
import { inject } from 'vue'

//todo 1. Dodać rememberstatelink 2. ustawić zapamiętywanie stany na usuń i restore 3. dodać polityki 4. wyświetlać odpowiednie przyciski według tego czy można coś z nimi zrobić
    
defineProps({
  object: Object,
})

const disableShowButton = inject('disable_show_button', false)
</script>