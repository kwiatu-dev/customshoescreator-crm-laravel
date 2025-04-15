<template>
  <div class="card">
    <h2><slot /></h2>
    <div class="total">{{ value }}</div>
  </div>
</template>

<script setup>
defineProps({
  value: {
    type: [String, Number],
    required: true,
  },
})
</script>