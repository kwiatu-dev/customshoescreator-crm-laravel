<template>
  <div v-if="filterable" class="relative">
    <button 
      class="btn-outline text-black bg-gray-300 border-0 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 px-4 py-2" 
      :class="{'!bg-gray-200 hover:!bg-gray-300 dark:!bg-gray-700 dark:hover:!bg-gray-800': !toggle}" 
      @click="open"
    >
      {{ toggle ? 'Filtry' : 'Ukryj' }}
    </button>
    <div id="dropdown" class="w-80 bg-gray-300 dark:bg-gray-800 p-4 rounded-lg absolute mt-2" :class="{'hidden': toggle}">
      <div class="flex flex-row flex-nowrap justify-between">
        <h6 class="dark:text-gray-200 font-medium">Filtry</h6>
        <div class="flex flex-row flex-nowrap gap-4">
          <span class="text-indigo-600 hover:underline dark:text-indigo-500 cursor-pointer" @click="clear">
            Resetuj
          </span>
        </div>
      </div>
      <div v-if="filterable?.search" class="mt-2">
        <div class="w-full relative">
          <input 
            id="search" 
            v-model="form.search" 
            type="text" 
            class="filter-input" 
            placeholder="Szukaj po słowach kluczowych..."
          />
        </div>
      </div>
      <div id="accordion-flush" class="mt-4">
        <div v-if="filterable?.price">
          <Heading :title="'Kwota'" :class="{'active': !sections['price']}" @click="expand('price')" />
          <Price :form="form" :class="{'hidden': sections['price']}" @filters-update="update" />
        </div>
        <div v-if="filterable?.visualization">
          <Heading :title="'Koszt wizualizacji'" :class="{'active': !sections['visualization']}" @click="expand('visualization')" />
          <VisualizationCost :form="form" :class="{'hidden': sections['visualization']}" @filters-update="update" />
        </div>
        <div v-for="(column) in filterable.date.columns" :key="column">
          <Heading :title="columns[column].label" :class="{'active': !sections[column]}" @click="expand(column)" />
          <Date :form="form" :class="{'hidden': sections[column]}" :column="column" @filters-update="update" /> 
        </div>
        <div v-if="filterable?.status">
          <Heading :title="'Status'" :class="{'active': !sections['status']}" @click="expand('status')" />
          <Status :form="form" :class="{'hidden': sections['status']}" @filters-update="update" />
        </div>
        <div v-if="filterable?.type">
          <Heading :title="'Typ'" :class="{'active': !sections['type']}" @click="expand('type')" />
          <Type :form="form" :class="{'hidden': sections['type']}" @filters-update="update" />
        </div>
        <div v-if="filterable?.pagination">
          <Heading :title="'Ilość'" :class="{'active': !sections['pagination']}" @click="expand('pagination')" />
          <Pagination :form="form" :class="{'hidden': sections['pagination']}" @filters-update="update" />
        </div>
        <div v-if="filterable?.others">
          <Heading :title="'Inne'" :class="{'active': !sections['others']}" @click="expand('others')" />
          <Others :form="form" :class="{'hidden': sections['others']}" :filterable="filterable.others" @filters-update="update" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Heading from '@/Components/UI/List/Filters/Heading.vue'
import Others from '@/Components/UI/List/Filters/Others.vue'
import Price from '@/Components/UI/List/Filters/Price.vue'
import VisualizationCost from '@/Components/UI/List/Filters/VisualizationCost.vue'
import Date from '@/Components/UI/List/Filters/Date.vue'
import Pagination from '@/Components/UI/List/Filters/Pagination.vue'
import Status from '@/Components/UI/List/Filters/Status.vue'
import Type from '@/Components/UI/List/Filters/Type.vue'
import { ref, reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'

const props = defineProps({
  filters: Object,
  filterable: Object,
  sort: Object,
  get: String,
  columns: Object,
})
  
const form = reactive({
  ...props.filters,
})

const sections = reactive({
  price: true,
  pagination: true,
  others: true,
  status: true,
  type: true,
  visualization: true,

  ...props.filterable.date.columns.reduce((acc, key) => {
    acc[key] = true
    return acc
  }, {}),
})

const toggle = ref(true)

const open = () => {
  toggle.value = !toggle.value
}

const expand = (id) => {
  sections[id] = !sections[id]
}
  
const clear = () => {
  for (let key of Object.keys(form)) {
    form[key] = null
  }
}

const update = (data) => {
  for(const key in data){
    form[key] = data[key]
  }
}

const clean = () => {
  return Object.keys(form).reduce((acc, key) => {
    if (form[key] && form[key] !== 'null') {
      acc[key] = form[key]
    }
    return acc
  }, {})
}
  
const filter = () => {
  router.get(
    route(props.get),
    {...clean(), ...props.sort},
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}

watch(form, debounce(filter, 1000))
</script>