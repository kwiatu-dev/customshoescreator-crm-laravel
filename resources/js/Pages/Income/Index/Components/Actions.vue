<template>
  <RememberStateButton v-if="object.project_id && !disableShowButton" label="Projekt" :url="route('projects.show', { project: object.project_id })" />
  <SettleIncomeAction v-if="object.settleable" :income="object" />
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('incomes.show', { income: object.id })" />
  <RememberStateButton :disabled="!object.editable" label="Edytuj" :url="route('incomes.edit', { income: object.id })" />
  <Link :disabled="!object.deletable" :href="route('incomes.destroy', { income: object.id })" method="delete" as="button" class="btn-action" preserve-scroll preserve-state>Usuń</Link>
  <Link :disabled="!object.restorable" :href="route('incomes.restore', { income: object.id })" method="put" as="button" class="btn-action" preserve-scroll preserve-state>Odzyskaj</Link>
</template>

<script setup>
import { inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'
import SettleIncomeAction from '@/Pages/Income/Index/Components/SettleIncomeAction.vue'

defineProps({
  object: Object,
})

const disableShowButton = inject('disable_show_button', false)
</script>