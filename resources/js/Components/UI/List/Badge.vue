<template>
  <div v-if="object.deleted_at" class="text-xs font-bold uppercase border border-dashed p-1 border-red-300 text-red-500 dark:border-red-600 dark:text-red-600 inline-block rounded-md mb-2 self-start">USUNIĘTE</div>
</template>

<script setup>
defineProps({
  object: Object,
})
</script>