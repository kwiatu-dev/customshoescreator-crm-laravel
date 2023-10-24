<template>
  <div class="flex flex-row flex-nowrap gap-2 items-center justify-start">
    <label class="font-medium text-sm dark:text-gray-200" :for="name">
      {{ label }}
    </label>
    <input :id="name" v-model="form[name]" type="checkbox" class="dark:bg-gray-600 rounded-md dark:border-gray-500 cursor-pointer" @change="$emit('filters-update', form)" />
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  form: Object,
  label: String,
  name: String,
})

const form = reactive({
  [props.name]: props.form[props.name] ?? null,
})

defineEmits(['filters-update'])

watch(props.form, () => {
  form[props.name] = props.form[props.name] ?? null
})
</script>