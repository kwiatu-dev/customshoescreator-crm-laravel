<template>
  <div class="container mx-auto p-4">
    <h1 class="title">Lista klientów</h1>
    <section class="flex items-start flex-row md:justify-between md:items-center mt-8">
      <div class="w-2/3 max-w-lg">
        <form class="flex flex-col items-start md:flex-row md:flex-nowrap md:items-center gap-2">
          <input v-model="filterForm.search" type="text" class="input h-11 rounded-r-none md:rounded-md" placeholder="Szukaj" />
          <div class="flex flex-row flex-norwap gap-2 items-center">
            <input id="deleted" v-model="filterForm.deleted" type="checkbox" class="cursor-pointer rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <label for="deleted" class="label whitespace-nowrap cursor-pointer text-gray-800 dark:text-gray-300">Pokaż usunięte</label>
            <button type="submit" class="btn-outline dark:bg-gray-600 bg-gray-100" @click.prevent="filterAndSort">Filtruj</button>
            <button v-if="props.filters.deleted || props.filters.search" type="reset" @click="clear">Reset</button>
          </div>
        </form>
      </div>
      <Link
        href="#" 
        class="btn-primary w-1/3 rounded-l-none h-11 text-center md:rounded-l-md md:w-auto md:text-left"
      >
        + Dodaj {{ !isLargeScreen ? '' : 'klienta' }}
      </Link>
    </section>
    <section v-if="isLargeScreen" class="overflow-auto my-2">
      <table>
        <thead>
          <tr>
            <td>Nazwa użytkownika <button class="ml-4" @click="sortBy('username')">{{ getSortSymbol('username') }}</button></td>
            <td>Imię <button class="ml-4" @click="sortBy('first_name')">{{ getSortSymbol('first_name') }}</button></td>
            <td>Nazwisko<button class="ml-4" @click="sortBy('last_name')">{{ getSortSymbol('last_name') }}</button></td>
            <td>Email<button class="ml-4" @click="sortBy('email')">{{ getSortSymbol('email') }}</button></td>
            <td>Telefon<button class="ml-4" @click="sortBy('phone')">{{ getSortSymbol('phone') }}</button></td>
            <td>Adres<button class="ml-4" @click="sortBy('street')">{{ getSortSymbol('street') }}</button></td>
            <td>Kod pocztowy<button class="ml-4" @click="sortBy('postcode')">{{ getSortSymbol('postcode') }}</button></td>
            <td>Miasto<button class="ml-4" @click="sortBy('city')">{{ getSortSymbol('city') }}</button></td>
            <td>Kraj<button class="ml-4" @click="sortBy('country')">{{ getSortSymbol('country') }}</button></td>
            <td>Źródło konwersji<button class="ml-4" @click="sortBy('conversion_source')">{{ getSortSymbol('conversion_source') }}</button></td>
            <td>Akcje</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="client in clients.data" :key="client.id" :class="{'bg-red-600 dark:bg-red-900': client.deleted_at}">
            <td>
              <a :href="client.social_link" class="text-indigo-600 hover:text-indigo-500">
                {{ client.username }}
              </a>
            </td>
            <td>{{ client.first_name }}</td>
            <td>{{ client.last_name }}</td>
            <td>{{ client.email }}</td>
            <td>{{ client.phone }}</td>
            <td>{{ client.street }} {{ client.street_nr }}</td>
            <td>{{ client.postcode }}</td>
            <td>{{ client.city }}</td>
            <td>{{ client.country }}</td>
            <td>{{ client.conversion_source }}</td>
            <td>
              <div class="flex flex-col items-start">
                <Link href="#" class="underline hover:text-gray-500">Edytuj</Link>
                <Link v-if="!client.deleted_at" :href="route('client.destroy', {client: client.id})" method="delete" as="button" class="underline  hover:text-gray-500">Usuń</Link>
                <Link v-if="client.deleted_at" :href="route('client.restore', {client: client.id})" method="put" as="button" class="underline  hover:text-gray-500">Odzyskaj</Link>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </section>
    <section v-if="!isLargeScreen" class="my-8 grid grid-cols-1 md:grid-cols-2 gap-4">
      <Box v-for="client in clients.data" :key="client.id" class="flex flex-col gap-2 px-6">
        <div v-if="client.deleted_at" class="text-xs font-bold uppercase border border-dashed p-1 border-red-300 text-red-500 dark:border-red-600 dark:text-red-600 inline-block rounded-md mb-2 self-start">USUNIĘTE</div>
        <div class="flex flex-row flex-nowrap gap-8">
          <div class="flex-1">
            <div class="text-xl mb-2">
              {{ client.first_name }} {{ client.last_name }}
            </div>
            <div class="text-gray-400">
              <a :href="client.social_link" class="text-indigo-600 hover:text-indigo-500">
                @{{ client.username }}
              </a>
            </div>
            <div class="text-gray-400">{{ client.email }}</div>
            <div class="text-gray-400">{{ client.phone }}</div>
          </div>
          <div class="flex-1">
            <div class="invisible mb-2">blank</div>
            <div class="text-gray-400">{{ client.street }} {{ client.street_nr }}</div>
            <div class="text-gray-400">{{ client.postcode }}, {{ client.city }}</div>
            <div class="text-gray-400">{{ client.country }}</div>
          </div>
        </div>
        <div class="flex flex-row flex-nowrap gap-2 mb-1 mt-4">
          <Link href="#" class="btn-outline">Edytuj</Link>
          <Link v-if="!client.deleted_at" :href="route('client.destroy', {client: client.id})" method="delete" as="button" class="btn-outline">Usuń</Link>
          <Link v-if="client.deleted_at" :href="route('client.restore', {client: client.id})" method="put" as="button" class="btn-outline">Odzyskaj</Link>
        </div>
      </Box>
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
import Pagination from '@/Components/UI/Pagination.vue'
import { Link, router } from '@inertiajs/vue3'
import { useMediaQuery } from '@vueuse/core'
import Box from '@/Components/UI/Box.vue'
import { reactive, watch } from 'vue'
import {debounce} from 'lodash'

const isLargeScreen = useMediaQuery('(min-width: 768px)')

const props = defineProps({
  filters: Object,
  clients: Object,
  sort: Object,
})

const filterForm = reactive({
  deleted: props.filters.deleted ?? null,
  search: props.filters.search ?? null,
})

const clear = () => {
  filterForm.deleted = null
  filterForm.search = null
  filterAndSort()
}

const sortForm = reactive({
  columns: props.sort.columns ?? {},
})

const sortBy = (column) => {
  sortForm.columns[column] = sortForm.columns[column] === 'desc' ? 'asc' : 'desc'
}

const getSortSymbol = (column) => {
  return sortSymbols[sortForm.columns[column]] ?? '↓'
}

const sortSymbols = {
  asc: '↓',
  desc: '↑',
}

watch(sortForm, debounce(() => {
  filterAndSort()
}, 1000))

const filterAndSort = () => {  
  router.get(
    route('client.index'),
    {...filteredFilterForm, ...sortForm},
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}
</script>
