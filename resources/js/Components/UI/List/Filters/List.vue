<template>
  <div class="flex flex-nowrap items-center py-4">
    <select 
      v-model="form[column]" 
      class="filter-input" 
      @change="$emit('filters-update', form)"
    >
      <option :value="null">Wybierz opcję</option>
      <option v-for="option in data" :key="option.value" :value="option.value">
        {{ option.name }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  form: Object,
  data: Object,
  column: String,
})

const form = reactive({
  [props.column]: props.form[props.column] ?? null,
})

defineEmits(['filters-update'])

watch(props.form, () => {
  form[props.column] = props.form[props.column] ?? null
})
</script>