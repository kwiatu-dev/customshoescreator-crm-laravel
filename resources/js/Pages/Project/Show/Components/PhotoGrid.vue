<template>
  <div v-if="photos?.length" class="flex flex-col gap-4 mt-1">
    <div v-for="photo in photos" :key="photo.id" class="w-32">
      <a :href="photo.url" download>
        <img :src="photo.url" class="w-32 aspect-square rounded-md" />
      </a>
    </div>
  </div>
  <div v-else class="w-full text-center p-4 bg-gray-800 rounded-md mt-1">
    Brak zdjęć
  </div>
</template>

<script setup>
defineProps({
  photos: { type: Array, required: true },
})
</script>