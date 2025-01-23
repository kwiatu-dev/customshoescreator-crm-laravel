<template>
  <Box v-for="object in objects" :key="object.id" class="flex flex-col gap-2 px-6">
    <Badge :object="object" />
    <div class="flex flex-row flex-wrap">
      <CardData
        v-for="([table, column, data], index) in d" 
        :key="table ? `${table}.${column}` : column" 
        :element="data" 
        :object="table ? object[table] : object" 
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
import { useListColumns } from '@/Composables/useListColumns'

const props = defineProps({
  objects: Array,
  cards: Object,
  actions: Object,
})

const d = useListColumns(props.cards)
</script>