<template>
  <RememberStateButton v-if="object.project_id" label="Projekt" :url="route('projects.show', { project: object.project_id })" />
  <!-- <Link v-if="object.project_id" :href="route('projects.show', { project: object.project_id })" class="btn-action" preserve-scroll preserve-state>Projekt</Link> -->
  <SettleIncomeAction v-if="object.settleable" :income="object" />
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('incomes.show', { income: object.id })" />
  <!-- <Link v-if="!disableShowButton" :href="route('incomes.show', { income: object.id })" class="btn-action" preserve-scroll preserve-state>Zobacz</Link> -->
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('incomes.edit', { income: object.id })" />
  <Link v-if="object.deletable" :href="route('incomes.destroy', { income: object.id })" method="delete" as="button" class="btn-action" preserve-scroll preserve-state>Usuń</Link>
  <Link v-if="object.restorable" :href="route('incomes.restore', { income: object.id })" method="put" as="button" class="btn-action" preserve-scroll preserve-state>Odzyskaj</Link>
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