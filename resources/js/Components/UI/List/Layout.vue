<template>
  <div>
    <h1 class="title">
      <slot name="title" />
    </h1>
    <section class="flex items-start flex-row md:justify-between md:items-center mt-8">
      <Filters :filters="filters" :sort="sort" :get="get" />
      <slot name="create" />
    </section>
    <section v-if="isLargeScreen" class="overflow-auto my-4">
      <Table 
        :columns="columns" 
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
  cards: Object,
  columns: Object,
  get: String,
  title: String,
  actions: Object,
})
</script>