<template>
  <Link v-if="!disableShowButton" :href="route('projects.show', { project: object.id })" as="button" class="btn-action">Zobacz</Link>
  <StartProjectAction v-if="!object.deleted_at && object.status_id === 1" :project="object" />
  <EndProjectAction v-if="!object.deleted_at && object.status_id === 2" :project="object" />
  <Link v-if="!object.deleted_at && object.editable === 1" :href="route('projects.edit', { project: object.id })" class="btn-action">Edytuj</Link>
  <Link v-if="!object.deleted_at && object.editable === 1" :href="route('projects.destroy', { project: object.id })" method="delete" as="button" class="btn-action">Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('projects.restore', { project: object.id })" method="put" as="button" class="btn-action">Odzyskaj</Link>
</template>
    
<script setup>
import { Link } from '@inertiajs/vue3'
import StartProjectAction from '@/Pages/Project/Index/Components/Actions/StartProjectAction.vue'
import EndProjectAction from '@/Pages/Project/Index/Components/Actions/EndProjectAction.vue'
import { inject } from 'vue'
    
defineProps({
  object: Object,
})

const disableShowButton = inject('disable_show_button', false)
</script>