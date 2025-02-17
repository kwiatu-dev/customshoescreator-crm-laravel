<template>
  <span>{{ total }}</span>
  <span> zł</span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  object: Object,
})

const total = computed(() => (parseFloat(props.object.amount) + (parseFloat(props.object.amount) * parseInt(props.object.interest_rate) / 100)).toFixed(2))
</script>