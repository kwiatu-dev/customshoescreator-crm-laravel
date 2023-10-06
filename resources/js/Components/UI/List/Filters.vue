<template>
  <div class="w-2/3 max-w-lg">
    <form class="flex flex-col items-start md:flex-row md:flex-nowrap md:items-center gap-2">
      <input v-model="form.search" type="text" class="input h-11 rounded-r-none md:rounded-md" placeholder="Szukaj" />
      <div class="flex flex-row flex-norwap gap-2 items-center">
        <input id="deleted" v-model="form.deleted" type="checkbox" class="cursor-pointer rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
        <label for="deleted" class="label whitespace-nowrap cursor-pointer text-gray-800 dark:text-gray-300">Pokaż usunięte</label>
        <button type="submit" class="btn-outline dark:bg-gray-600 bg-gray-100" @click.prevent="filter">Filtruj</button>
        <button v-if="props.filters.deleted || props.filters.search" type="reset" @click="clear">Reset</button>
      </div>
    </form>
  </div>
</template>
  
<script setup>
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
  
const props = defineProps({
  filters: Object,
  sort: Object,
  get: String,
})
  
const form = reactive({
  ...props.filters,
})
  
const clear = () => {
  form.deleted = null
  form.search = null
  filter()
}

const cleanFilter = () => {
  return Object.keys(form).reduce((acc, key) => {
    if (form[key]) {
      acc[key] = form[key]
    }
    return acc
  }, {})
}
  
const filter = () => {
  router.get(
    route(props.get),
    {...cleanFilter(), ...props.sort},
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}
</script>