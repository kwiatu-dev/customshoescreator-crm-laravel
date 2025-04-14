<template>
  <div class="col-span-12 flex flex-row flex-nowrap justify-center items-center">
    <div class="bg-gray-200 rounded-full px-1 py-1 flex flex-row flex-nowrap justify-between items-center gap-2 border border-solid border-gray-300">
      <button
        v-for="(label, index) in options"
        :key="index"
        :class="[
          'rounded-full px-2 py-1 text-xs font-medium',
          selectedOption === index ? 'shadow-md bg-white text-gray-800' : 'text-gray-600'
        ]"
        @click="selectOption(index)"
      >
        {{ label }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  options: {
    type: Array,
    required: true,
    default: () => ['Option 1', 'Option 2'],
  },
  modelValue: {
    type: Number,
    default: 0,
  },
})

const selectedOption = ref(props.modelValue)

const emit = defineEmits(['update:modelValue'])

const selectOption = (index) => {
  selectedOption.value = index
  emit('update:modelValue', index)
}

watch(() => props.modelValue, (newValue) => {
  selectedOption.value = newValue
})
</script>

<style scoped>
button {
  transition: all 0.2s ease-in-out;
}
button:hover {
  transform: scale(1.05);
}
</style>