<template>
  <Link v-if="auth?.is_admin && object?.income && object.income.deleted_at === null" :href="route('incomes.show', { income: object.income.id })" method="get" as="button" class="btn-action">Przychód</Link>
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('projects.show', { project: object.id })" />
  <StartProjectAction v-if="object.deletable && object.status_id === 1" :project="object" />
  <EndProjectAction v-if="object.deletable && object.status_id === 2" :project="object" />
  <RememberStateButton :disabled="!object.editable" label="Edytuj" :url="route('projects.edit', { project: object.id })" />
  <Link :disabled="!object.deletable" :href="route('projects.destroy', { project: object.id })" method="delete" as="button" class="btn-action" preserve-scroll>Usuń</Link>
  <Link :disabled="!object.restorable" :href="route('projects.restore', { project: object.id })" method="put" as="button" class="btn-action" preserve-scroll>Odzyskaj</Link>
</template>
    
<script setup>
import { inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import StartProjectAction from '@/Pages/Project/Index/Components/Actions/StartProjectAction.vue'
import EndProjectAction from '@/Pages/Project/Index/Components/Actions/EndProjectAction.vue'
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'
import { useAuthUser } from '@/Composables/useAuthUser'

defineProps({
  object: Object,
})

const auth = useAuthUser()
const disableShowButton = inject('disable_show_button', false)
</script>