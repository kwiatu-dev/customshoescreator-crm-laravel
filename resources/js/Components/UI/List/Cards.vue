<template>
  <Box v-for="object in objects" :key="object.id" class="flex flex-col gap-2 px-6">
    <Badge :object="object" />
    <div class="flex flex-row flex-wrap">
      <div v-for="(element, field) in cards" :key="field" class="w-1/2" :class="{'w-full text-lg font-bold mb-4': element.title}">
        <a v-if="element.link" :href="(element.link.prefix ?? '') + object[element.link.field] + (element.link.suffix ?? '')" target="_blank" class="text-indigo-600 hover:text-indigo-500">
          <span>{{ object[field] }} {{ element.suffix }}</span>
        </a>
        <span v-else>
          {{ object[field] }} {{ element.suffix }}
        </span>
        <span v-for="concat in element.concat" :key="concat">
          {{ (element.separator ?? ' ') + object[concat] }}
        </span>
      </div>
    </div>
    <div class="flex flex-row flex-nowrap gap-2 mb-1 mt-4">
      <component :is="actions" :object="object" />
    </div>
  </Box>
</template>
  
<script setup>
import Box from '@/Components/UI/Box.vue'
import Badge from '@/Components/UI/List/Badge.vue'
  
defineProps({
  objects: Array,
  cards: Object,
  actions: Object,
})
</script>