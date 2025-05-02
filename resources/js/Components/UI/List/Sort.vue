<template>
  <div v-if="order.length">
    Sortowanie: 
    <draggable 
      v-model="order" 
      tag="div" 
      group="sort" 
      item-key="column" 
      class="flex flex-row flex-nowrap gap-2 mb-2 mt-1"
      @end="sort"
    >
      <template #item="{ element, index }">
        <div class="py-2 px-4 text-sm border rounded-md border-gray-300 cursor-grab bg-gray-100 dark:bg-gray-800">
          <span>{{ label(element) }}</span>
          <span class="ml-3 font-bold cursor-pointer p-1" @click="remove(element, index)">
            <font-awesome-icon :icon="['far', 'circle-xmark']" />
          </span>
        </div>
      </template>
    </draggable>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { reactive, watch, ref } from 'vue'
import debounce from 'lodash/debounce'
import mergeWith from 'lodash/mergeWith'
import isObject from 'lodash/isObject'
import draggable from 'vuedraggable'
import { useQueryParams } from '@/Composables/useQueryParams'

const props = defineProps({
  columns: Object,
  sort: Object, 
  filters: Object,
  orderBy: Object,
  page: Number,
  get: String,
})

const emit = defineEmits(['sortTable'])

const form = reactive({
  ...props.sort ?? null,
})

const label = (element) => {
  if (props.columns?.[element]?.label) {
    return props.columns[element].label
  } else if (element.includes('.')) {
    const [parent, child] = element.split('.')
    return props.columns?.[parent]?.columns?.[child]?.label || 'BRAK NAZWY'
  }

  return 'BRAK NAZWY'
}

const remove = (field) => {
  delete form[field]
}

const order = ref(Object.keys(props.sort ?? null))

const withOrder = () => {
  if (order.value.length <= 1) return form

  const final = {}

  for (const field of order.value) {
    if (form[field]) {
      final[field] = form[field]
    }
  }

  return mergeWith({}, final, form, (value, src) => {
    if (isObject(value)) {
      return Object.assign({}, value, src)
    }
  })
}

const query = () => {
  const params = useQueryParams()
  const sort = withOrder()

  for (const key of Object.keys(params)) {
    if (props.sort.hasOwnProperty(key)) {
      delete params[key]
    }
  }

  return { ...params, ...sort }
}

function isFullUrl(url) {
  try {
    new URL(url)
    return true
  } catch {
    return false
  }
}

const sort = () => {
  const urlOrRoute = isFullUrl(props.get) ? props.get : route(props.get)

  if (props.page > 1) query['page'] = props.page

  router.get(urlOrRoute, query(), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      order.value = Object.keys(props.sort)
      emit('sortTable', form)
    },
  })
}

watch(form, debounce(() => sort(), 600))
watch(() => props.orderBy, (value) => sortTable(value.field))

const sortTable = (field) => {
  form[field] = form[field] === 'desc' ? 'asc' : 'desc'
}
</script>
