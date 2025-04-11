<template>
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('projects.show', { project: object.id })" />
  <StartProjectAction v-if="object.status_id === 1" :project="object" />
  <EndProjectAction v-if="object.status_id === 2" :project="object" />
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('projects.edit', { project: object.id })" />
  <Link v-if="object.deletable" :href="route('projects.destroy', { project: object.id })" method="delete" as="button" class="btn-action" preserve-scroll>Usuń</Link>
  <Link v-if="object.restorable" :href="route('projects.restore', { project: object.id })" method="put" as="button" class="btn-action" preserve-scroll>Odzyskaj</Link>
</template>
    
<script setup>
import { inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import StartProjectAction from '@/Pages/Project/Index/Components/Actions/StartProjectAction.vue'
import EndProjectAction from '@/Pages/Project/Index/Components/Actions/EndProjectAction.vue'
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'

defineProps({
  object: Object,
})

const disableShowButton = inject('disable_show_button', false)
</script>