<template>
  <div v-if="photos?.length" class="flex flex-row gap-4 mt-1">
    <div v-for="photo in photos" :key="photo.id" class="w-32">
      <img
        v-fullscreen-image="{
          imageUrl: photos.map(p => p.url),
          withDownload: false,
          closeOnClickOutside: true,
          withClose: false,
          animation: 'blur',
        }" :src="photo.url" class="w-32 aspect-square object-cover rounded-md"
      />
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