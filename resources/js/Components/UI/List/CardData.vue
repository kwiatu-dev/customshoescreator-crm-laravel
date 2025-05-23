<template>
  <div v-if="onlyAdmin" class="inline">
    <template v-if="link">
      <a 
        v-if="isExternalLink" 
        :href="link"  
        class="text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-500 hover:text-indigo-600"
        :style="props.element.title ? '' : 'display: inline-block; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'"
      >
        <component :is="props.element.component" v-if="hasComponent" :object="object" />
        <span v-else>{{ data }}</span>
      </a>
      <Link 
        v-else
        :href="link"  
        class="text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-500 hover:text-indigo-600"
        :style="props.element.title ? '' : 'display: inline-block; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'"
      >
        <component :is="props.element.component" v-if="hasComponent" :object="object" />
        <span v-else>{{ data }}</span>
      </Link>
    </template>
    <div 
      v-else 
      class="inline" 
      :style="[props.element.title || props.element.remarks ? '' : 'display: inline-block; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;']"
    >
      <component :is="props.element.component" v-if="hasComponent" :object="object" />
      <span v-else>{{ data }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

const props = defineProps({
  element: Object,
  object: Object,
  field: String,
})

const page = usePage()

const currentUser = computed(
  () => page.props.currentUser,
)

const onlyAdmin = computed(() => 
  (Object.hasOwn(props.element, 'admin') ===  true && currentUser.value?.is_admin == true) ||
  (Object.hasOwn(props.element, 'admin') === false), 
)

const hasComponent = computed(() => typeof props.element.component === 'object')

const data = computed(() => {
  let value = ''
  const separator = (props.element?.separator || ' ')

  if(props.element?.blank === true){
    return value
  }

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

  if(props.element?.concat)
  {
    const c = props.element.concat
      .map(field => {
        if (props.object?.[field]) {
          return props.object[field]
        }
      })
      .join(separator)

    if (c) {
      value += separator + c
    }
  }

  if (value === '') {
    return ''
  }

  return `${props.element?.prefix || ''}${value}${props.element?.suffix || ''}`
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

const isExternalLink = computed(() => link.value.startsWith('mailto:') || link.value.startsWith('tel:'))
</script>