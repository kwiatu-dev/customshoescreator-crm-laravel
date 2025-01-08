<template>
  <select class="input" :value="modelValue" @change="handleSelect">
    <option 
      v-for="item in source"
      :key="props.id(item)" 
      :value="props.id(item)"
    >
      {{ props.name(item) }}
    </option>
  </select>
</template>

<script setup>
const props = defineProps({
  source: {
    type: Array,
    required: true,
  },
  id: {
    type: Function,
    required: true,
  },
  name: {
    type: Function,
    required: true,
  },
  modelValue: [String, Number],
})

const emit = defineEmits(['update:modelValue'])

const handleSelect = (event) => {
  emit('update:modelValue', event.target.value)
}
</script>