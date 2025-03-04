<template>
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('repayments.index', { investment: object.id })" />
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('investments.edit', { investment: object.id })" />
  <Link v-if="object.deletable" :href="route('investments.destroy', { investment: object.id })" method="delete" as="button" class="btn-action" preserve-scroll preserve-state>Usuń</Link>
  <Link v-if="object.restorable" :href="route('investments.restore', { investment: object.id })" method="put" as="button" class="btn-action" preserve-scroll preserve-state>Odzyskaj</Link>
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
