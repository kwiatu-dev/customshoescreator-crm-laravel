<template>
  <div>
    <h1 class="title">Lista klientów</h1>
    <section class="flex items-start flex-row md:justify-between md:items-center mt-8">
      <ClientFilters :filters="filters" :sort="sort" />
      <Link
        :href="route('client.create')" 
        class="btn-primary w-1/3 rounded-l-none h-11 text-center md:rounded-l-md md:w-auto md:text-left"
      >
        + Dodaj {{ !isLargeScreen ? '' : 'klienta' }}
      </Link>
    </section>
    <section v-if="isLargeScreen" class="overflow-auto my-4">
      <ClientTable :clients="clients.data" :sort="sort" :filters="filters" :page="clients.current_page" />
    </section>
    <section v-if="!isLargeScreen" class="my-4 grid grid-cols-1 md:grid-cols-2 gap-4">
      <ClientCards :clients="clients.data" />
    </section>
    <section class="flex flex-col-reverse md:flex-row justify-between items-center">
      <div>Wyświetlanie rekordów od {{ clients.from }} do {{ clients.to }} z {{ clients.total }} </div>
      <div>
        <Pagination :links="clients.links" />
      </div>
    </section>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { useMediaQuery } from '@vueuse/core'
import Pagination from '@/Components/UI/List/Pagination.vue'
import ClientTable from '@/Pages/Client/Index/Components/ClientTable.vue'
import ClientFilters from '@/Pages/Client/Index/Components/ClientFilters.vue'
import ClientCards from '@/Pages/Client/Index/Components/ClientCards.vue'

const isLargeScreen = useMediaQuery('(min-width: 768px)')

defineProps({
  filters: Object,
  clients: Object,
  sort: Object,
})
</script>
