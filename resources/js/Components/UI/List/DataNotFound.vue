<template>
  <div class="bg-gray-800 rounded-md p-4 mt-4">     
    <div class="text-center" style="font-size: 50px">
      <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
    </div> 
    <h2 class="text-center text-2xl font-bold mt-2">
      Nie znaleziono danych spełniających podane kryteria
    </h2>
    <div class="line mt-4 mb-4" />
    <div v-for="(value, field) in filters" :key="field">
      {{ field }} => {{ value }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  filters: Object,
})
</script>