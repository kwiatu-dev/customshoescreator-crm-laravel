<template>
  <div class="w-2/3 max-w-lg">
    <form class="flex flex-col items-start md:flex-row md:flex-nowrap md:items-center gap-2">
      <input v-model="filterForm.search" type="text" class="input h-11 rounded-r-none md:rounded-md" placeholder="Szukaj" />
      <div class="flex flex-row flex-norwap gap-2 items-center">
        <input id="deleted" v-model="filterForm.deleted" type="checkbox" class="cursor-pointer rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
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
})

const filterForm = reactive({
  ...props.filters,
})

const clear = () => {
  filterForm.deleted = null
  filterForm.search = null
  filter()
}

const filter = () => {
  const finalFilterObject = Object.keys(filterForm).reduce((acc, key) => {
    if (filterForm[key]) {
      acc[key] = filterForm[key]
    }
    return acc
  }, {})

  router.get(
    route('client.index'),
    {...finalFilterObject, ...props.sort},
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}
</script>

<!-- <script setup>
import { reactive } from 'vue'

const props = defineProps({
  filters: Object,
})

const emit = defineEmits(['filterUpdated'])

const filterForm = reactive({
  deleted: props.filters.deleted ?? null,
  search: props.filters.search ?? null,
})

const clearFilter = () => {
  filterForm.deleted = null
  filterForm.search = null
  filterUpdated()
}

const filterUpdated = () => {
  emit('filterUpdated', filterForm)
}
</script> -->