<template>
  <ListLayout 
    :objects="users" 
    :filters="filters" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :cards="cards"
    :filterable="filterable"
    :get="'user.index'"
    :actions="Actions"
  >
    <template #title>
      Lista użytkowników
    </template>
    <template #create>
      <Link
        :href="route('user.create')" 
        class="btn-primary px-4"
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
    </template>
  </ListLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineAsyncComponent, provide } from 'vue'

const ListLayout = defineAsyncComponent(() => import('@/Components/UI/List/Layout.vue'))
const AdminDistribution = defineAsyncComponent(() => import('@/Components/UI/List/AdminDistribution.vue'))
const Actions = defineAsyncComponent(() => import('@/Pages/User/Index/Components/Actions.vue'))

const props = defineProps({
  users: Object,
  admins: Array,
  filters: Object,
  sort: Object,
})

const columns = {
  first_name: { label: 'Imię'},
  last_name: { label: 'Nazwisko' },
  email: { label: 'Email', link: {field: 'email', prefix: 'mailto:'}},
  phone: { label: 'Telefon', link: {field: 'phone', prefix: 'tel:'}},
  street: { label: 'Ulica' },
  street_nr: { label: 'Numer ulicy' },
  apartment_nr: { label: 'Numer mieszkania' },
  postcode: { label: 'Kod pocztowy' },
  city: { label: 'Miasto' },
  country: { label: 'Kraj' },
  commission: { label: 'Prowizja', suffix: '%' },
  costs: { label: 'Koszty', suffix: '%' },
  distribution: { label: 'Podział', component: AdminDistribution },
}

const cards = {
  first_name: {title: true, concat: ['last_name']},
  email: {link: {field: 'email', prefix: 'mailto:'}},
  street: {concat: ['street_nr', 'apartment_nr']},
  phone: {link: {field: 'phone', prefix: 'tel:'}},
  postcode: {concat: ['city'], separator: ', '},
}

const filterable = {
  search: {},
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' } ],
}

const sortable = {
  first_name: true,
  last_name: true,
  email: true,
  street: true,
  street_nr: true,
  apartment_nr: true,
  city: true,
  country: true,
  commission: true,
  costs: true,
}

provide('users', props.admins)
</script>