<template>
  <ListLayout 
    :objects="users" 
    :filters="filters" 
    :sort="sort" 
    :columns="columns" 
    :cards="cards"
    :filterable="filterable"
    :get="'user.index'"
    :actions="actions"
  >
    <template #title>
      Lista użytkowników
    </template>
    <template #create>
      <Link
        :href="route('user.create')" 
        class="btn-primary w-1/3 rounded-l-none h-11 text-center md:rounded-l-md md:w-auto md:text-left"
      >
        + Dodaj <span class="hidden md:inline">użytkownika</span>
      </Link>
    </template>
  </ListLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import actions from '@/Pages/User/Index/Components/Actions.vue'

defineProps({
  users: Object,
  filters: Object,
  sort: Object,
})

const columns = {
  first_name: { label: 'Imię', 'sortable': true},
  last_name: { label: 'Nazwisko', 'sortable': true },
  email: { label: 'Email', link: {field: 'email', prefix: 'mailto:'}, 'sortable': true},
  phone: { label: 'Telefon', link: {field: 'phone', prefix: 'tel:'}},
  street: { label: 'Ulica', 'sortable': true },
  street_nr: { label: 'Numer ulicy', 'sortable': true },
  apartment_nr: { label: 'Numer mieszkania', 'sortable': true },
  postcode: { label: 'Kod pocztowy' },
  city: { label: 'Miasto', 'sortable': true },
  country: { label: 'Kraj', 'sortable': true },
  commission: { label: 'Prowizja', 'sortable': true },
  costs: { label: 'Koszty', 'sortable': true },
  distribution: { label: 'Podział' },
}

const cards = {
  first_name: {concat: ['last_name']},
  street: {concat: ['street_nr', 'apartment_nr']},
  email: {link: {field: 'email', prefix: 'mailto:'}},
  postcode: {concat: ['city'], separator: ', '},
  phone: {link: {field: 'phone', prefix: 'tel:'}},
  country: {},
}

const filterable = {
  search: {},
  others: { deleted: {} },
}
</script>