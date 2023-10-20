<template>
  <div class="flex flex-nowrap items-center py-4">
    <select 
      v-model="form.status_id" 
      class="filter-input" 
      @change="$emit('filters-update', form)"
    >
      <option value="null">Wybierz opcję</option>
      <option value="1">Oczekujące</option>
      <option value="2">W trakcie realizacji</option>
      <option value="3">Zakończone</option>
    </select>
  </div>
</template>
  
<script setup>
import { reactive, watch } from 'vue'
  
const props = defineProps({
  form: Object,
})
  
const form = reactive({
  status_id: props.form.status_id ?? null,
})
  
defineEmits(['filters-update'])
  
watch(props.form, () => {
  form.status_id = props.form.status_id ?? null
})
</script>