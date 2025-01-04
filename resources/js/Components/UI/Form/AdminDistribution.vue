<template>
  <div class="flex flex-col gap-4">
    <div v-for="admin in admins" :key="admin.id" class="flex flex-row flex-nowrap justify-between items-center">
      <div>
        {{ admin.first_name }} {{ admin.last_name }}
      </div>
      <div class="line" />
      <div>
        <input ref="percentage" type="number" class="input w-16" min="0" max="100" step="1" :value="distribution[admin.id]" :data-user-id="admin.id" @input="input" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  users: {
    required: true,
    type: Array,
  },
  distribution: {
    required: true,
    type: Object,
  },
  modelValue: Object,
})

const percentage = ref(null)

const admins = props.users.filter((user) => user.is_admin === 1 && user.deleted_at === null)

const input = (event) => {
  if (event.target.value > 100) {
    event.target.value = 100
  }

  const distribution = percentage.value.reduce(
    (acc, el) => { 
      acc[parseInt(el.getAttribute('data-user-id'))] = el.value 
      return acc 
    }, {})

  emit('update:modelValue', distribution)
}

const emit = defineEmits(['update:modelValue'])
</script>

<style scoped>
.line {
  flex-grow: 1; 
  height: 1px; 
  @apply border-b border-dotted border-gray-400 mx-4;
}
</style>