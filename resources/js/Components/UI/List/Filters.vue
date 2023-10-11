<template>
  <div class="relative">
    <button class="px-4 py-2 bg-gray-800 rounded-lg" @click="open">{{ toggle ? 'Filtrowanie ↓' : 'Schowaj ↑' }}</button>
    <div id="dropdown" class="w-80 bg-gray-800 p-4 rounded-lg absolute mt-2" :class="{'hidden': toggle}">
      <div class="flex flex-row flex-nowrap justify-between">
        <h6 class="text-gray-200 font-medium">Filtry</h6>
        <div class="flex flex-row flex-nowrap gap-4">
          <span class="text-indigo-500 cursor-pointer hover:text-indigo-400" @click="clear">
            Resetuj
          </span>
        </div>
      </div>
      <div class="mt-2">
        <div class="w-full relative">
          <input id="search" v-model="form.search" type="text" class="w-full bg-gray-600 border-gray-500 rounded-lg placeholder:text-gray-400 placeholder:text-sm dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Szukaj po słowach kluczowych..." />
        </div>
      </div>
      <div id="accordion-flush" class="mt-4">
        <div v-if="filterable.price">
          <Heading :title="'Kwota'" @click="expand('price')" />
          <Price :form="form" :class="{'hidden': sections['price']}" @filters-update="update" />
        </div>
        <div v-if="filterable.date">
          <Heading :title="'Data'" @click="expand('date')" />
          <Date :form="form" :class="{'hidden': sections['date']}" @filters-update="update" />
        </div>
        <div v-if="filterable.others">
          <Heading :title="'Inne'" @click="expand('others')" />
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
import Date from '@/Components/UI/List/Filters/Date.vue'
import { ref, reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'

const props = defineProps({
  filters: Object,
  filterable: Object,
  sort: Object,
  get: String,
})
  
const form = reactive({
  ...props.filters,
})

const sections = reactive({
  price: true,
  date: true,
  others: true,
})

const toggle = ref(true)

const open = () => {
  toggle.value = !toggle.value
}

const expand = (id) => {
  sections[id] = !sections[id]
}
  
const clear = () => {
  if(form.deleted)
    form.deleted = null
  if(form.search)
    form.search = null
  if(form.date_start)
    form.date_start = null
  if(form.date_end)
    form.date_end = null
  if(form.price_start)
    form.price_start = null
  if(form.price_end)
    form.price_end = null
  if(form.date_start)
    form.date_start = null
  if(form.date_end)
    form.date_end = null
}

const update = (data) => {
  for(const key in data){
    form[key] = data[key]
  }
}

const clean = () => {
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
    {...clean(), ...props.sort},
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}

watch(form, debounce(filter, 1000))
</script>