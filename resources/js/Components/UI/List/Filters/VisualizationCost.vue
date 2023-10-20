<template>
  <div class="flex flex-nowrap items-center py-4">
    <input
      v-model.number="form.visualization_start"
      type="number"
      placeholder="Od"
      class="filter-input rounded-r-none"
      @input="$emit('filters-update', form)"
    />
    <input
      v-model.number="form.visualization_end"
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
})
  
const form = reactive({
  visualization_start: props.form.visualization_start ?? null,
  visualization_end: props.form.visualization_end ?? null,
})
  
defineEmits(['filters-update'])
  
watch(props.form, () => {
  form.visualization_start = props.form.visualization_start ?? null
  form.visualization_end = props.form.visualization_end ?? null
})
  
</script>