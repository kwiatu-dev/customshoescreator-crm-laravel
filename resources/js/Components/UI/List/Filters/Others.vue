<template>
  <div id="others" class="py-4">
    <div class="">
      <ul class="">
        <li class="flex flex-row flex-nowrap gap-2 items-center justify-start">
          <label class="font-bold text-sm text-gray-200" for="deleted">
            Pokaż usunięte
          </label>
          <input id="deleted" v-model="form.deleted" type="checkbox" class="bg-gray-600 rounded-md border-gray-500 cursor-pointer" @change="$emit('filters-update', form)" />
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const props = defineProps({
  form: Object,
})

const form = reactive({
  ...props.form,
})

defineEmits(['filters-update'])
</script>