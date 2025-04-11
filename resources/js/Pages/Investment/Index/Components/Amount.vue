<template>
  <span>{{ total_repayment }}</span>
  <span> z </span>
  <span>{{ total }}</span>
  <span> zł</span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  object: Object,
})

const total_repayment = computed(() => parseFloat(props.object.total_repayment).toFixed(2))
const total = computed(() => (parseFloat(props.object.amount) + (parseFloat(props.object.amount) * parseInt(props.object.interest_rate) / 100)).toFixed(2))
</script>