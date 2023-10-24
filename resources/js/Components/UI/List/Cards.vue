<template>
  <Box v-for="object in objects" :key="object.id" class="flex flex-col gap-2 px-6">
    <Badge :object="object" />
    <div class="flex flex-row flex-wrap">
      <div 
        v-for="(element, field) in cards" 
        :key="field" 
        class="w-1/2" 
        :class="{
          'w-full mt-4': element.remarks,
          'w-full text-lg font-bold mb-4': element.title
        }"
      >
        <CardData :element="element" :object="object" :field="field" />
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
import CardData from '@/Components/UI/List/CardData.vue'

defineProps({
  objects: Array,
  cards: Object,
  actions: Object,
})
</script>