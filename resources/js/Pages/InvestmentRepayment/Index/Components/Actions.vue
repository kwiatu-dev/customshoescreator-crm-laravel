<template>
  <RememberStateButton v-if="object.editable" label="Edytuj" :url="route('repayments.edit', { investment: object.investment_id, repayment: object.id })" />
  <Link :disabled="!object.deletable" :href="route('repayments.destroy', { investment: object.investment_id, repayment: object.id })" method="delete" as="button" class="btn-action" preserve-scroll preserve-state>Usuń</Link>
  <Link :disabled="!object.restorable" :href="route('repayments.restore', { investment: object.investment_id, repayment: object.id })" method="put" as="button" class="btn-action" preserve-scroll preserve-state>Odzyskaj</Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'

defineProps({
  object: Object,
})
</script>
