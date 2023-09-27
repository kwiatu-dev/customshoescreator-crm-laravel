<template>
  <div v-if="sortOrder.length">
    Kolejność sortowania: 
    <draggable 
      v-model="sortOrder" 
      tag="div" 
      group="sort" 
      item-key="column" 
      class="flex flex-row flex-nowrap gap-2 mb-2 mt-1"
      @end="sortTable"
    >
      <template #item="{ element: column }">
        <div class="py-2 px-4 text-sm border rounded-md border-gray-300 cursor-grab bg-gray-800">{{ column }}</div>
      </template>
    </draggable>
  </div>
  <table>
    <thead>
      <tr>
        <td>Nick <button class="ml-4" @click="sortBy('username')">{{ getSortSymbol('username') }}</button></td>
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
      <tr v-for="client in clients" :key="client.id" :class="{'bg-red-600 dark:bg-red-900': client.deleted_at}">
        <td>
          <a :href="client.social_link" class="text-indigo-600 hover:text-indigo-500">
            {{ client.username }}
          </a>
        </td>
        <td>{{ client.first_name }}</td>
        <td>{{ client.last_name }}</td>
        <td>{{ client.email }}</td>
        <td>{{ client.phone }}</td>
        <td>
          <ClientAddress :client="client" />
        </td>
        <td>{{ client.postcode }}</td>
        <td>{{ client.city }}</td>
        import LoginVue from '@/Pages/Auth/Login.vue';
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
</template>

<script setup>
import ClientAddress from '@/Pages/Client/Index/Components/ClientAddress.vue'
import { Link, router } from '@inertiajs/vue3'
import { reactive, watch, ref } from 'vue'
import { debounce } from 'lodash'
import draggable from 'vuedraggable'

const props = defineProps({
  clients: Array,
  filters: Object,
  sort: Object,
})

const sortForm = reactive({
  ...props.sort ?? null,
})

const sortOrder = ref(Object.keys(sortForm))

const sortBy = (column) => {
  sortForm[column] = sortForm[column] === 'desc' ? 'asc' : 'desc'
  sortOrder.value = Object.keys(sortForm)
}

const getSortSymbol = (column) => {
  return sortSymbols[sortForm[column]] ?? '↓'
}

const sortSymbols = {
  asc: '↓',
  desc: '↑',
}

const getSortObject = () => {
  const finalSort = {}

  for (const column of sortOrder.value) {
    if(sortForm[column] ?? false){
      finalSort[column] = sortForm[column]
    }
  }

  return finalSort
}

const sortTable = () => {
  router.get(
    route('client.index'),
    {...getSortObject(), ...props.filters},
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}

watch(sortForm, debounce(() => sortTable(), 1000))
</script>
