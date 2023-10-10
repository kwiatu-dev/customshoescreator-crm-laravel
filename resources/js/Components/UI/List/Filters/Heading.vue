<template>
  <h2>
    <button type="button" class="w-full flex flex-row flex-nowrap justify-between items-center border-b border-gray-600 py-2">
      <span class="text-gray-400 font-medium">{{ title }}</span>
      <svg aria-hidden="true" class="w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
      </svg>
    </button>
  </h2>
</template>

<script setup>
defineProps({
  title: String,  
})
</script>