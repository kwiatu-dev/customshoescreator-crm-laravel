<template>
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('client.show', { client: object.id })" />
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('client.edit', { client: object.id })" />
  <Link v-if="object.deletable" :href="route('client.destroy', {client: object.id})" method="delete" as="button" class="btn-action" preserve-state preserve-scroll>Usuń</Link>
  <Link v-if="object.restorable" :href="route('client.restore', {client: object.id})" method="put" as="button" class="btn-action" preserve-state preserve-scroll>Odzyskaj</Link>
</template>
    
<script setup>
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'
import { Link } from '@inertiajs/vue3'
import { inject } from 'vue'
    
defineProps({
  object: Object,
})

const disableShowButton = inject('disable_show_button', false)
</script>