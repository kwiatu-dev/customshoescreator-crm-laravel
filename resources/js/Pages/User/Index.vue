<template>
  <div>
    <h1 class="title">Lista klientów</h1>
    <section class="flex items-start flex-row md:justify-between md:items-center mt-8">
      <!-- <ClientFilters :filters="filters" :sort="sort" />
      <Link
        :href="route('client.create')" 
        class="btn-primary w-1/3 rounded-l-none h-11 text-center md:rounded-l-md md:w-auto md:text-left"
      >
        + Dodaj {{ !isLargeScreen ? '' : 'klienta' }}
      </Link> -->
    </section>
    <section v-if="isLargeScreen" class="overflow-auto my-4">
      <Table :labels="labels" :objects="users.data" :filters="filters" :sort="sort" />
    </section>
    <section v-if="!isLargeScreen" class="my-4 grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- <ClientCards :clients="clients.data" /> -->
    </section>
    <section class="flex flex-col-reverse md:flex-row justify-between items-center">
      <div>Wyświetlanie rekordów od {{ users.from }} do {{ users.to }} z {{ users.total }} </div>
      <div>
        <Pagination :links="users.links" />
      </div>
    </section>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { useMediaQuery } from '@vueuse/core'
import Table from '@/Components/UI/Table.vue'
import Pagination from '@/Components/UI/Pagination.vue'

const isLargeScreen = useMediaQuery('(min-width: 768px)')

const props = defineProps({
  users: Object,
  filters: Object,
  sort: Object,
})

const labels = {
  first_name: 'Imię',
  last_name: 'Nazwisko',
  email: 'Email',
  phone: 'Telefon',
  street: 'Ulica',
  street_nr: 'Numer ulicy',
  apartment_nr: 'Numer mieszkania',
  postcode: 'Kod pocztowy',
  city: 'Miasto',
  country: 'Kraj',
  commission: 'Prowizja',
  costs: 'Koszty',
  distribution: 'Podział',
}
</script>