<template>
  <template v-if="link">
    <a 
      v-if="isExternalLink" 
      :href="link"
      :target="props.element.link?.target || null"  
      class="text-indigo-400 hover:text-indigo-500"
    >
      <component :is="props.element.component" v-if="hasComponent" :object="object" />
      <span v-else>{{ cell }}</span>
    </a>
    <Link 
      v-else
      :href="link"  
      class="text-indigo-400 hover:text-indigo-500"
    >
      <component :is="props.element.component" v-if="hasComponent" :object="object" />
      <span v-else>{{ cell }}</span>
    </Link>
  </template>
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

  if (!value) {
    return ''
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

  if (!value) {
    return ''
  }

  return `${props.element.link?.prefix || ''}${value}${props.element.link?.suffix || ''}`
})

const isExternalLink = computed(() => link.value.startsWith('mailto:') || link.value.startsWith('tel:') || props.element.link?.external)
const hasComponent = computed(() => typeof props.element.component === 'object')
</script>