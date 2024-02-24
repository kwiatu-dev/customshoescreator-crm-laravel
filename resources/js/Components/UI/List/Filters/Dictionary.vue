<template>
  <div v-if="loaded" class="flex flex-nowrap items-center py-4">
    <select 
      v-model="form[column]" 
      class="filter-input" 
      @change="$emit('filters-update', form)"
    >
      <option :value="null">Wybierz opcję</option>
      <option v-for="option in options" :key="option.id" :value="option.id">
        {{ option.name }}
      </option>
    </select>
  </div>
</template>
      
<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import axios from 'axios'
      
const props = defineProps({
  form: Object,
  table: String,
  column: String,
})
      
const form = reactive({
  [props.column]: props.form[props.column] ?? null,
})

const options = ref([])
const loaded = ref(false)

onMounted(() => {
  axios.get(route('dictionary.index', {table: props.table}))
    .then(response => {
      options.value = response.data
      loaded.value = true
    })
    .catch(error => {
      loaded.value = false
    })
})
      
defineEmits(['filters-update'])
      
watch(props.form, () => {
  form[props.column] = props.form[props.column] ?? null
})
</script>