<template>
  <ListLayout 
    :objects="users" 
    :filters="filters" 
    :sort="sort" 
    :columns="columns" 
    :cards="cards"
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
  first_name: { label: 'Imię' },
  last_name: { label: 'Nazwisko' },
  email: { label: 'Email', link: 'email', prefix: 'mailto:'},
  phone: { label: 'Telefon', link: 'phone', prefix: 'tel:'},
  street: { label: 'Ulica' },
  street_nr: { label: 'Numer ulicy' },
  apartment_nr: { label: 'Numer mieszkania' },
  postcode: { label: 'Kod pocztowy' },
  city: { label: 'Miasto' },
  country: { label: 'Kraj' },
  commission: { label: 'Prowizja' },
  costs: { label: 'Koszty' },
  distribution: { label: 'Podział' },
}

const cards = {
  first_name: {concat: ['last_name']},
  street: {concat: ['street_nr', 'apartment_nr']},
  email: {link: 'email', prefix: 'mailto:'},
  postcode: {concat: ['city'], separator: ', '},
  phone: {link: 'phone', prefix: 'tel:'},
  country: {},
}
</script>