<template>
  <div class="flex flex-nowrap items-center py-4">
    <select 
      v-model="form.type_id" 
      class="filter-input" 
      @change="$emit('filters-update', form)"
    >
      <option value="null">Wybierz opcję</option>
      <option value="1">Renowacja butów</option>
      <option value="2">Personalizacja butów</option>
      <option value="3">Personalizacja ubrań</option>
      <option value="4">Haft ręczny</option>
      <option value="5">Haft komputerowy</option>
      <option value="6">Inne</option>
    </select>
  </div>
</template>
    
<script setup>
import { reactive, watch } from 'vue'
    
const props = defineProps({
  form: Object,
})
    
const form = reactive({
  type_id: props.form.type_id ?? null,
})
    
defineEmits(['filters-update'])
    
watch(props.form, () => {
  form.type_id = props.form.type_id ?? null
})
</script>