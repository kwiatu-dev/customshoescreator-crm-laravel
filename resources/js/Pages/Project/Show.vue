<template>
  <div class="flex flex-row flex-wrap gap-4">
    <div class="basis-full sm:flex-1  border border-gray-200 dark:border-gray-800 rounded-md p-4 shadow-sm dark:text-gray-300">
      <div v-if="project.images?.length" class="flex flex-col gap-4">
        <div v-if="project.images.some(image => image.type_id === 1)">
          <div class="text-lg font-medium text-gray-300 mb-2 border-b border-gray-500">Inspiracje</div>
          <div v-for="image in project.images" :key="image.id" class="w-32">
            <a :href="image.url" download>
              <img :src="image.url" class="w-32 aspect-square rounded-md" />
            </a>
          </div>
        </div>
      </div>
      <div v-else>
        Brak zdjęć
      </div>
    </div>
    <div class="basis-full sm:flex-1  border border-gray-200 dark:border-gray-800 rounded-md p-4 shadow-sm dark:text-gray-300">
      Box 2
    </div>
  </div>
</template>

<script setup>
defineProps({
  project: Object,
})
</script>