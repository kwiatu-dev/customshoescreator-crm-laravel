<template>
  <a 
    v-if="link" 
    :href="link" 
    target="_blank" 
    class="text-indigo-600 hover:text-indigo-500"
  >
    {{ cell }}
  </a>
  <span v-else>
    {{ cell }}
  </span>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  object: Object,
  field: String,
  element: Object,
})

const cell = computed(() => {
  let value = ''
    
  if(props.element?.columns){
    value = props.element.columns.map(key => props.object[props.field][key]).join(' ')
  }
  else{
    value = props.object[props.field]
  }

  return `${props.element.prefix || ''}${value}${props.element.suffix || ''}`
})

const link = computed(() => {
  let value = ''
    
  if(props.element?.link){
    const field = props.element.link?.field
    const column = props.element.link?.column

    if(field)
      value = column ? props.object[column][field] : props.object[field]
  }

  return `${props.element.link?.prefix || ''}${value}${props.element.link?.suffix || ''}`
})
</script>