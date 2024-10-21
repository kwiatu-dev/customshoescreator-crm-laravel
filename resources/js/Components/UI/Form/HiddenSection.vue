<template>
  <div class="flex flex-col">
    <div class="flex flex-row justify-between flex-nowrap w-full border-b border-gray-400 px-2 py-2 mb-2 cursor-pointer hover:bg-gray-600" :class="{'bg-red-900': danger}" @click="toggle">
      <div>{{ title }}</div>
      <button :class="{'rotate-180': !hidden}">↓</button>
    </div>
    <div class="w-full flex flex-col justify-center md:grid md:grid-cols-6 gap-4 overflow-hidden" :class="{ 'h-0': hidden }">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  title: {
    required: true,
    type: String,
  },
  danger: {
    default: false,
    type: Boolean,
  },
})

const hidden = ref(true)

const toggle = () => hidden.value = !hidden.value
</script>