<template>
  <Box v-for="object in objects" :key="object.id" class="flex flex-col gap-2 px-6">
    <Badge :object="object" />
    <div class="flex flex-row flex-wrap">
      <CardData
        v-for="(element, field, index) in withoutMultipleColumns" 
        :key="field" 
        :element="element" 
        :object="object" 
        :field="field"
        class="w-1/2"
        :class="{
          'w-full mt-4': element.remarks,
          'w-full text-lg font-bold mb-4': element.title,
          'w-full': element.fullWidth,
          'text-right': (element.order || index + 1) % 2 !== 0
        }"
        :style="{ order: element.order || 'unset' }"
      />
      <CardData
        v-for="([table, column, data], index) in withMultipleColumns" 
        :key="`${table}.${column}`" 
        :element="data" 
        :object="object[table]" 
        :field="column"
        class="w-1/2"
        :class="{
          'w-full mt-4': data.remarks,
          'w-full text-lg font-bold mb-4': data.title,
          'w-full': data.fullWidth,
          'text-right': (data.order || index + 1) % 2 !== 0
        }"
        :style="{ order: data.order || 'unset' }"
      />
    </div>
    <div class="flex flex-row flex-nowrap gap-2 mb-1 mt-4">
      <component :is="actions" :object="object" />
    </div>
  </Box>
</template>
  
<script setup>
import Box from '@/Components/UI/List/Box.vue'
import Badge from '@/Components/UI/List/Badge.vue'
import CardData from '@/Components/UI/List/CardData.vue'
import { computed } from 'vue'

const props = defineProps({
  objects: Array,
  cards: Object,
  actions: Object,
})

const withMultipleColumns = computed(() =>
  Object.entries(props.cards)
    .filter(([_, element]) => typeof element.columns === 'object' && !Array.isArray(element.columns))
    .flatMap(([table, element]) =>
      Object.entries(element.columns).map(([column, data]) => [table, column, data]),
    ),
)

const withoutMultipleColumns = computed(() => Object.fromEntries(Object.entries(props.cards).filter(([field, element], index) => {
  return element.columns === undefined || Array.isArray(element.columns)
})))
</script>