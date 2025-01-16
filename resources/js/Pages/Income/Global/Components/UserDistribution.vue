<template>
  <div v-if="users?.length" class="flex flex-col gap-4">
    <div v-for="user in users" :key="user.id" class="flex flex-row flex-nowrap justify-between items-center">
      <div>
        {{ user.first_name }} {{ user.last_name }}
      </div>
      <div class="line" />
      <div>
        <input ref="percentage" type="number" class="input w-16" min="0" max="100" step="1" :value="distribution?.[user.id] || ''" :data-user-id="user.id" @input="input" />
      </div>
    </div>
  </div>
</template>
  
<script setup>
import { ref, onMounted, watch } from 'vue'
  
const props = defineProps({
  users: {
    required: true,
    type: Array,
  },
  distribution: {
    required: true,
    type: [Object, null],
  },
  modelValue: Object,
})
  
const percentage = ref(null)
const value = ref(null)
  
const input = (event) => {
  if (event?.target?.value > 100) {
    event.target.value = 100
  }

  if (percentage.value) {
    value.value = percentage.value.reduce(
      (acc, el) => { 
        acc[parseInt(el.getAttribute('data-user-id'))] = el.value 
        return acc 
      }, {})
  }
  
  emit('update:modelValue', value)
}
  
onMounted(() => {
  input(null)
  emit('update:modelValue', value)
})

watch(() => percentage.value, () => input(null), { deep: true })
  
const emit = defineEmits(['update:modelValue'])
</script>
  
  <style scoped>
  .line {
    flex-grow: 1; 
    height: 1px; 
    @apply border-b border-dotted border-gray-400 mx-4;
  }
  </style>