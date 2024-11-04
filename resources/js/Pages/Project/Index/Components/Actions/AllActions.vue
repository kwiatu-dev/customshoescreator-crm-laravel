<template>
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('projects.show', { project: object.id })" />
  <StartProjectAction v-if="canStartProject" :project="object" />
  <EndProjectAction v-if="canEndProject" :project="object" />
  <RememberStateButton v-if="!object.deleted_at && object.editable === 1" label="Edytuj" :url="route('projects.edit', { project: object.id })" />
  <Link v-if="canDestroyProject" :href="route('projects.destroy', { project: object.id })" method="delete" as="button" class="btn-action" preserve-scroll>Usuń</Link>
  <Link v-if="canRestoreProject" :href="route('projects.restore', { project: object.id })" method="put" as="button" class="btn-action" preserve-scroll>Odzyskaj</Link>
</template>
    
<script setup>
import { inject, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import StartProjectAction from '@/Pages/Project/Index/Components/Actions/StartProjectAction.vue'
import EndProjectAction from '@/Pages/Project/Index/Components/Actions/EndProjectAction.vue'
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'

const props = defineProps({
  object: Object,
})

const canStartProject = computed(() => !props.object.deleted_at && props.object.status_id === 1)
const canEndProject = computed(() => !props.object.deleted_at && props.object.status_id === 2)
const canDestroyProject = computed(() => !props.object.deleted_at && props.object.editable === 1)
const canRestoreProject = computed(() => props.object.deleted_at)

const disableShowButton = inject('disable_show_button', false)
</script>