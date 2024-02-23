<template>
  <div class="w-full relative">
    <input type="hidden" :value="modelValue" />
    <input v-model="search" type="text" :class="classes" @input="handleInput" />
    <ul 
      v-show="searchResults.length && isOpen"
      class="mt-1 w-full max-h-60 border border-gray-200 dark:border-gray-400 rounded-md bg-gray-200 dark:bg-gray-800 absolute overflow-y-auto z-10"
    >
      <li 
        v-for="result in searchResults" 
        :key="props.id(result)"
        :data-id="props.id(result)"
        :data-name="props.name(result)"
        class="px-4 py-4 border-b border-gray-400 dark:border-gray-300 text-gray-900 dark:text-gray-300 cursor-pointer hover:bg-gray-300 hover:dark:bg-gray-700 transiction-colors"
        @click="setSelected"
      >
        {{ props.list(result) }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  source: {
    type: Array,
    required: true,
    default: () => [],
  },
  id: {
    type: Function,
    required: true,
  },
  fields: {
    type: Array,
    required: true,
  },
  name: {
    type: Function,
    required: true,
  },
  list: {
    type: Function,
    required: true,
  },
  classes: {
    type: String,
    required: false,
    default: 'input',
  },
  modelValue: String,
})

const emit = defineEmits(['update:modelValue'])
const search = ref('')
const isOpen = ref(false)

const searchResults = computed(() => {
  if(search.value === '' || search.value.length < 3){
    return []
  }

  return props.source.filter(item => {
    return props.fields.some(field => {
      return item[field]
        .toLowerCase()
        .includes(
          search.value.toLowerCase())
    })
  })
})

const setSelected = (event) => {
  search.value = event.target.getAttribute('data-name')
  isOpen.value = false
  emit('update:modelValue', event.target.getAttribute('data-id'))
}

const handleInput = (_) => {
  isOpen.value = true
}
</script>