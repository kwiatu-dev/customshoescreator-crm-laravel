<template>
  <div class="flex flex-nowrap items-center py-4">
    <input
      v-model.number="form.price_start"
      type="number"
      placeholder="Od"
      class="input-filter-l w-1/2 bg-gray-600 border-gray-500 placeholder:text-gray-400 font-normal text-sm"
      @change="$emit('filters-update', form)"
    />
    <input
      v-model.number="form.price_end"
      type="number"
      placeholder="Do" 
      class="input-filter-r w-1/2 bg-gray-600 border-gray-500 placeholder:text-gray-400 font-normal text-sm"
      @change="$emit('filters-update', form)"
    />
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  form: Object,
})

const form = reactive({
  price_start: props.form.price_start ?? null,
  price_end: props.form.price_end ?? null,
})

defineEmits(['filters-update'])

watch(props.form, () => {
  form.price_start = props.form.price_start ?? null
  form.price_end = props.form.price_end ?? null
})

</script>