<template>
  <ListLayout 
    :objects="clients" 
    :filters="filters"
    :filterable="filterable" 
    :sort="sort" 
    :sortable="sortable"
    :columns="columns" 
    :cards="cards"
    :get="'client.index'"
    :actions="actions"
  >
    <template #title>
      Lista klientów
    </template>
    <template #create>
      <Link
        :href="route('client.create')" 
        class="btn-primary px-4"
      >
        <font-awesome-icon :icon="['fas', 'plus']" />
      </Link>
    </template>
  </ListLayout>
</template>
  
<script setup>
import { defineAsyncComponent } from 'vue'
import { Link } from '@inertiajs/vue3'

const ListLayout = defineAsyncComponent(() => import('@/Components/UI/List/Layout.vue'))
const actions = defineAsyncComponent(() => import('@/Pages/Client/Index/Components/Actions.vue'))
  
defineProps({
  clients: Object,
  filters: Object,
  sort: Object,
})
  
const columns = {
  username: { label: 'Nick', link: { field: 'social_link', external: true, target: '_blank' }},
  first_name: { label: 'Imię' },
  last_name: { label: 'Naziwsko' },
  email: { label: 'Email', link: { field: 'email', prefix: 'mailto:' }},
  phone: { label: 'Telefon', link: { field: 'phone', prefix: 'tel:' }},
  street: { label: 'Ulica' },
  street_nr: { label: 'Numer ulicy' },
  apartment_nr: { label: 'Numer mieszkania' },
  postcode: { label: 'Kod pocztowy' },
  city: { label: 'Miasto' },
  country: { label: 'Kraj' },
  conversion_source: { columns: ['name'], label: 'Źródło konwersji' },
}
  
const cards = {
  first_name: { title: true, concat: ['last_name'] },
  email: { link: { field: 'email', prefix: 'mailto:' }},
  street: { concat: ['street_nr', 'apartment_nr'] },
  phone: { link: { field: 'phone', prefix: 'tel:' }},
  postcode: { concat: ['city'], separator: ', ' },
}
  
const filterable = {
  search: {},
  dictionary: [ 
    { table: 'ConversionSource', column: 'conversion_source_id', label: 'Źródło konwersji' },
  ], 
  pagination: {},
  others: [ { name: 'deleted', label: 'Pokaż usunięte' }, { name: 'created_by_user', label: 'Pokaż moje' } ],
}

const sortable = { 
  username: true,
  first_name: true,
  last_name: true,
  street: true,
  street_nr: true,
  apartment_nr: true,
  city: true,
  country: true,
}
</script>