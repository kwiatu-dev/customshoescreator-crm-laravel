<template>
  <div v-if="onlyAdmin" class="inline">
    <a 
      v-if="link" 
      :href="link" 
      target="_blank" 
      class="text-indigo-600 hover:text-indigo-500"
    >
      <component :is="props.element.component" v-if="hasComponent" :object="object" />
      <span v-else>{{ data }}</span>
    </a>
    <div v-else class="inline">
      <component :is="props.element.component" v-if="hasComponent" :object="object" />
      <span v-else>{{ data }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

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
    value = props.element.columns.map(key => props.object[props.field][key]).join(' ')
  }
  else{
    value = props.object[props.field]
  }

  if(props.element?.concat)
  {
    value += separator + props.element.concat
      .map(field => props.object[field])
      .join(separator)
  }


  return `${props.element?.prefix || ''}${value}${props.element?.suffix || ''}`
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