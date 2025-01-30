<template>
  <div>
    <h1 class="title">
      <slot name="title" />
    </h1>
    <section class="flex flex-row justify-between md:items-center mt-8">
      <Filters 
        :filters="filters" 
        :filterable="filterable" 
        :sort="sort" 
        :get="get" 
        :columns="columns" 
      />
      <slot name="create" />
    </section>
    <div v-if="objects.data.length">
      <section v-if="isLargeScreen" class="overflow-auto my-4 table-element" scroll-region>
        <Table 
          :styles="styles"
          :columns="columns" 
          :footer="footer"
          :objects="objects.data" 
          :filters="filters" 
          :sort="sort" 
          :sortable="sortable"
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
      <section class="flex flex-col-reverse md:flex-row justify-between items-center px-2 bg-gray-800 lg:-mt-4 py-4 rounded-md lg:rounded-none">
        <div>Wyświetlanie rekordów od {{ objects.from }} do {{ objects.to }} z {{ objects.total }} </div>
        <Pagination :links="objects.links" />
      </section>
    </div>
    <div v-else>
      <DataNotFound :filters="filters" />
    </div>
  </div>
</template>

<script setup>
import { useMediaQuery } from '@vueuse/core'
import Filters from '@/Components/UI/List/Filters.vue'
import Table from '@/Components/UI/List/Table.vue'
import Cards from '@/Components/UI/List/Cards.vue'
import Pagination from '@/Components/UI/List/Pagination.vue'
import DataNotFound from '@/Components/UI/List/DataNotFound.vue'
import { nextTick, onMounted } from 'vue'

const isLargeScreen = useMediaQuery('(min-width: 768px)')

defineProps({
  styles: Object,
  objects: Object,
  sort: Object,
  sortable: Object,
  filters: Object,
  filterable: Object,
  cards: Object,
  columns: Object,
  footer: Object,
  get: String,
  title: String,
  actions: Object,
})

onMounted(() => {
  nextTick(() => {
    const params = new URLSearchParams(window.location.search)
    const element = document.querySelector('.table-element')

    const scrollX = params.get('scrollX')
    const scrollY = params.get('scrollY')

    if (scrollY)
      window.scrollTo({ top: scrollY })

    if (scrollX)
      element.scrollTo({ left: scrollX })
  })
})
</script>