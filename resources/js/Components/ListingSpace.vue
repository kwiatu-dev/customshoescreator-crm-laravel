<template>
  <div>
    <span class="font-bold ">{{ listing.beds }} bds</span>
    <span class="text-gray-400 mx-2">|</span> 
    <span class="font-bold ">{{ listing.baths }} ba</span>
    <span class="text-gray-400 mx-2">|</span> 
    <span class="font-bold ">{{ listing.area }} m2</span>
  </div>
</template>

<script setup>
defineProps({
  listing: Object,
})
</script>

