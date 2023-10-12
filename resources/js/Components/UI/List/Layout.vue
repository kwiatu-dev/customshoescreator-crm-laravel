<template>
  <div>
    <h1 class="title">
      <slot name="title" />
    </h1>
    <section class="flex flex-row justify-between md:items-center mt-8">
      <Filters :filters="filters" :filterable="filterable" :sort="sort" :get="get" />
      <slot name="create" />
    </section>
    <div v-if="objects.data.length">
      <section v-if="isLargeScreen" class="overflow-auto my-4">
        <Table 
          :columns="columns" 
          :footer="footer"
          :objects="objects.data" 
          :filters="filters" 
          :sort="sort" 
          :page="objects.current_page" 
          :get="get" 
          :actions="actions"
        />
      </section>
      <section v-if="!isLargeScreen" class="my-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <Cards 
          :cards="cards"
          :objects="objects.data" 
          :actions="actions"
        />
      </section>
      <section class="flex flex-col-reverse md:flex-row justify-between items-center">
        <div>Wyświetlanie rekordów od {{ objects.from }} do {{ objects.to }} z {{ objects.total }} </div>
        <div>
          <Pagination :links="objects.links" />
        </div>
      </section>
    </div>
    <div v-else>
      <div>      
        <h2 class="text-2xl font-bold mt-8">
          Ups... nie znaleziono danych spełniających podane kryteria
        </h2>
        <div v-for="(value, field) in filters" :key="field" class="border-t border-gray-300 mt-4 py-2">
          {{ field }} => {{ value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMediaQuery } from '@vueuse/core'
import Filters from '@/Components/UI/List/Filters.vue'
import Table from '@/Components/UI/List/Table.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Pagination from '@/Components/UI/Pagination.vue'

const isLargeScreen = useMediaQuery('(min-width: 768px)')

defineProps({
  objects: Object,
  sort: Object,
  filters: Object,
  filterable: Object,
  cards: Object,
  columns: Object,
  footer: Object,
  get: String,
  title: String,
  actions: Object,
})
</script>