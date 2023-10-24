<template>
  <div class="flex flex-nowrap items-center py-4">
    <input
      v-model.number="form[price_start]"
      type="number"
      placeholder="Od"
      class="filter-input rounded-r-none"
      @input="$emit('filters-update', form)"
    />
    <input
      v-model.number="form[price_end]"
      type="number"
      placeholder="Do" 
      class="filter-input rounded-l-none"
      @input="$emit('filters-update', form)"
    />
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  form: Object,
  column: String,
})

const price_start = `${props.column}_start`
const price_end = `${props.column}_end`

const form = reactive({
  [price_start]: props.form[price_start] ?? null,
  [price_end]: props.form[price_end] ?? null,
})

defineEmits(['filters-update'])

watch(props.form, () => {
  form[price_start] = props.form[price_start] ?? null
  form[price_end] = props.form[price_end] ?? null
})

</script>