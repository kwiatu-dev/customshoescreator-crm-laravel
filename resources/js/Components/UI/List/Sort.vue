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
        <div class="py-2 px-4 text-sm border rounded-md border-gray-300 cursor-grab bg-gray-800">
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
import { _, debounce } from 'lodash'
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

const sortTable = (field) => {
  form[field] = form[field] === 'desc' ? 'asc' : 'desc'
}

const form = reactive({
  ...props.sort ?? null,
})

const label = (element) => {
  let value = null

  if (props.columns?.[element]?.label) {
    value = props.columns?.[element]?.label
  }
  else if (element.includes('.')) {
    const structure = element.split('.') 

    value = props.columns?.[structure[0]]?.columns?.[structure[1]]?.label 
  }

  return value || 'BRAK NAZWY'
}

const remove = (field) => {
  delete form[field]
}

const order = ref(
  Object.keys(props.sort ?? null),
)

const withOrder = () => {
  if(order.value.length <= 1)
    return form

  const final = {}

  for (const field of order.value) {
    if(form[field] ?? false){
      final[field] = form[field]
    }
  }

  return _.mergeWith({}, final, form, (value, src) => {
    if (_.isObject(value)) {
      return _.merge({}, value, src)
    }
  })
}

const query = () => {
  const params = useQueryParams()
  const sort = withOrder()

  for (const [key, value] of Object.entries(params)) {
    if (props.sort.hasOwnProperty(key)) {
      delete params[key]
    }
  }

  return { ...params, ...sort }
}

const sort = () => {
  if(props.page > 1) 
    query['page'] = props.page

  router.get(
    route(props.get),
    query(),
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        order.value = Object.keys(props.sort)
        emit('sortTable', form)
      },
    },
  )
}

watch(form, debounce(() => sort(), 600))
watch(() => props.orderBy, (value) => sortTable(value.field))
</script>