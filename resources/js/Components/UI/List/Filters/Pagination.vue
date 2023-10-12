<template>
  <div class="flex flex-nowrap items-center py-4">
    <select 
      v-model="form.pagination" 
      class="filter-input" 
      @change="$emit('filters-update', form)"
    >
      <option value="null">Wybierz opcję</option>
      <option value="10">10</option>
      <option value="20">20</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  form: Object,
})

const form = reactive({
  pagination: props.form.pagination ?? null,
})

defineEmits(['filters-update'])

watch(props.form, () => {
  form.pagination = props.form.pagination ?? null
})

</script>