<template>
  <div>      
    <h2 class="text-2xl font-bold mt-8">
      Ups... nie znaleziono danych spełniających podane kryteria
    </h2>
    <div v-for="(value, field) in filters" :key="field" class="border-t border-gray-300 mt-4 py-2">
      {{ field }} => {{ value }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  filters: Object,
})
</script>