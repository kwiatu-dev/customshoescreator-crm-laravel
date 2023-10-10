<template>
  <div class="flex flex-nowrap items-center py-4">
    <input
      v-model="form.price_start"
      type="number"
      placeholder="Od"
      class="input-filter-l w-1/2 bg-gray-600 border-gray-500 placeholder:text-gray-400 font-normal text-sm"
      @input="$emit('filters-update', form)"
    />
    <input
      v-model="form.price_end"
      type="number"
      placeholder="Do" 
      class="input-filter-r w-1/2 bg-gray-600 border-gray-500 placeholder:text-gray-400 font-normal text-sm"
      @input="$emit('filters-update', form)"
    />
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const props = defineProps({
  form: Object,
})

const form = reactive({
  ...props.form,
})

defineEmits(['filters-update'])
</script>