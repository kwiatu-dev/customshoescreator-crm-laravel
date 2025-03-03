<template>
  <div v-if="filterable" class="relative">
    <button 
      ref="filterShowButton"
      class="btn-outline text-black bg-gray-300 border-0 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 px-4 py-2" 
      :class="{'!bg-gray-200 hover:!bg-gray-300 dark:!bg-gray-700 dark:hover:!bg-gray-800': !toggle}" 
      @click="open"
    >
      <font-awesome-icon v-if="toggle" :icon="['fas', 'filter']" />
      <font-awesome-icon v-if="!toggle" :icon="['fas', 'filter-circle-xmark']" />
    </button>
    <div id="dropdown" ref="filterDropdownBox" class="w-80 bg-gray-300 dark:bg-gray-800 p-4 rounded-lg absolute mt-2 z-10" :class="{'hidden': toggle}">
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
        <div v-for="(column) in filterable?.numeric?.columns" :key="column">
          <Heading :title="columns[column].label" :class="{'active': !sections[column]}" @click="expand(column)" />
          <Price :form="form" :class="{'hidden': sections[column]}" :column="column" @filters-update="update" /> 
        </div>
        <div v-for="(column) in filterable?.date?.columns" :key="column">
          <Heading :title="columns[column].label" :class="{'active': !sections[column]}" @click="expand(column)" />
          <Date ref="filterDate" :form="form" :class="{'hidden': sections[column]}" :column="column" @filters-update="update" /> 
        </div>
        <div v-for="(object, index) in filterable?.dictionary" :key="index">
          <Heading v-if="admin(object.admin)" :title="object.label" :class="{'active': !sections[object.column]}" @click="expand(object.column)" />
          <Dictionary v-if="admin(object.admin)" :form="form" :class="{'hidden': sections[object.column]}" :column="object.column" :table="object.table" @filters-update="update" /> 
        </div>
        <div v-if="filterable?.pagination">
          <Heading :title="'Ilość'" :class="{'active': !sections['pagination']}" @click="expand('pagination')" />
          <Pagination :form="form" :class="{'hidden': sections['pagination']}" @filters-update="update" />
        </div>
        <div v-if="filterable?.others">
          <Heading :title="'Inne'" :class="{'active': !sections['others']}" class="pb-3" @click="expand('others')" />
          <div v-for="(object, index) in filterable.others" :key="index" :class="{'hidden': sections['others']}">
            <Others v-if="admin(object.admin)" :form="form" :label="object.label" :name="object.name" @filters-update="update" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Heading from '@/Components/UI/List/Filters/Heading.vue'
import Others from '@/Components/UI/List/Filters/Others.vue'
import Price from '@/Components/UI/List/Filters/Price.vue'
import Date from '@/Components/UI/List/Filters/Date.vue'
import Pagination from '@/Components/UI/List/Filters/Pagination.vue'
import Dictionary from '@/Components/UI/List/Filters/Dictionary.vue'
import { ref, reactive, watch, computed, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import { usePage } from '@inertiajs/vue3'
import { useQueryParams } from '@/Composables/useQueryParams'

const page = usePage()

const currentUser = computed(
  () => page.props.currentUser,
)

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
  pagination: true,
  others: true,

  ...props.filterable.dictionary?.map(item => item.column).reduce((acc, key) => {
    acc[key] = true
    return acc
  }, {}),

  ...props.filterable?.numeric?.columns.reduce((acc, key) => {
    acc[key] = true
    return acc
  }, {}),

  ...props.filterable?.date?.columns.reduce((acc, key) => {
    acc[key] = true
    return acc
  }, {}),
})

const toggle = ref(true)
const admin = (flag) => (flag === true && currentUser.value?.is_admin || flag === undefined)

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

const query = () => {
  const params = useQueryParams()

  for (const [key, value] of Object.entries(form)) {
    if (!params.hasOwnProperty(key)) {
      params[key] = value
    }
  }

  return Object.keys(params).reduce((acc, key) => {
    if (form.hasOwnProperty(key)) {
      if (form[key] && form[key] !== 'null') {
        acc[key] = params[key]
      }
    } 
    else {
      acc[key] = params[key]
    }
    return acc
  }, {})
}
  
const filter = () => {
  router.get(
    route(props.get),
    query(),
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}

watch(form, debounce(filter, 1000))

const filterShowButton = ref(null)
const filterDropdownBox = ref(null)
const filterDate = ref([])
let doubleClickRequired = false

const handleClickOutsideFilterBox = (event) => {
  if (event.button === 0) {
    const isClickedOutsideFilterBox = filterDropdownBox.value && !filterDropdownBox.value.contains(event.target)
    const isClickedOutsideFilterShowButton = filterShowButton.value && !filterShowButton.value.contains(event.target)
    const isFilterDateOpen = filterDate.value && filterDate.value.some(dateElement => dateElement.pickerStart.active === true || dateElement.pickerEnd.active === true)
    
    if (isFilterDateOpen) {
      doubleClickRequired = true
    }

    if (isClickedOutsideFilterBox && isClickedOutsideFilterShowButton && !isFilterDateOpen) {
      if (doubleClickRequired) {
        doubleClickRequired = false
        return
      }
      
      if (!toggle.value) {
        toggle.value = true
      }
    }
  }
}

onMounted(() => {
  document.addEventListener('mousedown', (event) => {
    setTimeout(() => {
      handleClickOutsideFilterBox(event)
    }, 0)
  })
})

onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleClickOutsideFilterBox)
})
</script>