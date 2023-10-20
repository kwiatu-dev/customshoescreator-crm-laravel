<template>
  <div id="others" class="py-4">
    <div class="">
      <ul class="">
        <li v-if="filterable.created_by_user" class="flex flex-row flex-nowrap gap-2 items-center justify-start">
          <label class="font-medium text-sm dark:text-gray-200" for="deleted">
            Pokaż moje
          </label>
          <input id="deleted" v-model="form.created_by_user" type="checkbox" class="dark:bg-gray-600 rounded-md dark:border-gray-500 cursor-pointer" @change="$emit('filters-update', form)" />
        </li>
        <li v-if="filterable.deleted" class="flex flex-row flex-nowrap gap-2 items-center justify-start">
          <label class="font-medium text-sm dark:text-gray-200" for="deleted">
            Pokaż usunięte
          </label>
          <input id="deleted" v-model="form.deleted" type="checkbox" class="dark:bg-gray-600 rounded-md dark:border-gray-500 cursor-pointer" @change="$emit('filters-update', form)" />
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  form: Object,
  filterable: Object,
})

const form = reactive({
  deleted: props.form.deleted ?? null,
  created_by_user: props.form.created_by_user ?? null,
})

defineEmits(['filters-update'])

watch(props.form, () => {
  form.deleted = props.form.deleted ?? null
  form.created_by_user = props.form.created_by_user ?? null
})
</script>