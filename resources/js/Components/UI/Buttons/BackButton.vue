<template>
  <div v-if="history && history.length > 1" class="mb-4">
    <button class="btn-action" @click="back">← Cofnij</button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const history = computed(() => window.history)

const back = () => {
  window.history.back()
}
</script>