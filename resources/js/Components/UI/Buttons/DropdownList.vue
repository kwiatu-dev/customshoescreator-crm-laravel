<template>
  <div class="relative my-2">
    <button 
      class="px-4 py-2 bg-gray-600 rounded-sm"
      :value="modelValue"
      @click="toggleDropdown"
    >
      {{ modelValue ? modelValue : caption }}
    </button>
    <ul class="absolute bg-gray-500 z-10 mt-1 rounded-sm border-gray-400 border" :class="[{ 'hidden' : !visible }]">
      <li v-for="(option, index) in options" :key="index" class="w-full">
        <a 
          class="w-full inline-block hover:bg-gray-600 p-2 px-4" 
          href="#"
          @click="handleOptionClick(option)"
        >
          {{ option }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  caption: {
    type: String,
    default: 'Wybierz element z listy',
  },
  options: { 
    required: true,
    type: Array,
  },
  modelValue: String,
})

const visible = ref(false)

const toggleDropdown = () => {
  visible.value = !visible.value
}

const handleOptionClick = (option) => {
  visible.value = false
  emit('update:modelValue', option)
}

const emit = defineEmits(['update:modelValue'])
</script>