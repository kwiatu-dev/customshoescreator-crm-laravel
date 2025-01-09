<template>
  <Link 
    v-if="link" 
    :href="link"  
    class="text-indigo-600 hover:text-indigo-500"
  >
    <component :is="props.element.component" v-if="hasComponent" :object="object" />
    <span v-else>{{ cell }}</span>
  </Link>
  <div v-else class="inline">
    <component :is="props.element.component" v-if="hasComponent" :object="object" />
    <span v-else>{{ cell }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  object: Object,
  field: String,
  element: Object,
})

const cell = computed(() => {
  let value = ''
    
  if(props.element?.columns){
    value = props.element.columns.map(key => {
      if (props.object?.[props.field]?.[key]) {
        return props.object[props.field][key]
      }
    }).join(' ')
  }
  else{
    if (props.object?.[props.field]) {
      value = props.object[props.field]
    }
  }

  return `${props.element.prefix || ''}${value}${props.element.suffix || ''}`
})

const link = computed(() => {
  let value = ''
    
  if(props.element?.link){
    const field = props.element.link?.field
    const column = props.element.link?.column

    if (column && !props.object?.[column]?.[field]) {
      return ''
    }

    if (!column && !props.object?.[field]) {
      return ''
    }

    if(field)
      value = column ? props.object[column][field] : props.object[field]
  }

  return `${props.element.link?.prefix || ''}${value}${props.element.link?.suffix || ''}`
})

const hasComponent = computed(() => typeof props.element.component === 'object')
</script>