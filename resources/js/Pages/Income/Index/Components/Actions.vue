<template>
  test
  <!-- <Link v-if="object.project_id" :href="route('projects.show', { project: object.project_id })" class="btn-action" preserve-scroll preserve-state>Projekt</Link>
  <Link v-if="object.created_by_user_id" :href="route('user.show', { user: object.created_by_user_id })" class="btn-action" preserve-scroll preserve-state>Użytkownik</Link>
  <Link v-if="!disableShowButton" :href="route('incomes.show', { income: object.id })" class="btn-action" preserve-scroll preserve-state>Zobacz</Link>
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('incomes.edit', { income: object.id })" />
  <Link v-if="object.deletable" :href="route('incomes.destroy', { income: object.id })" class="btn-action" preserve-scroll preserve-state>Usuń</Link>
  <Link v-if="object.restorable" :href="route('incomes.restore', { income: object.id })" class="btn-action" preserve-scroll preserve-state>Odzyskaj</Link> -->
</template>

<script setup>
import { inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'

defineProps({
  object: Object,
})

const disableShowButton = inject('disable_show_button', false)
</script>