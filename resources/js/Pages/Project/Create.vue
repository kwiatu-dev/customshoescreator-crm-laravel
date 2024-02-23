<template>
  <form class="container mx-auto p-4" autocomplete="off" @submit.prevent="create">
    <h1 class="title">Dodaj projekt</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-6">
        <label for="created_by_user" class="label">Wykonawca</label>
        <Autocomplete 
          :id="(item) => item.id" 
          v-model="form.created_by_user" 
          :source="users"
          :fields="['first_name', 'last_name', 'email']"
          :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
          :name="(item) => `${item.first_name} ${item.last_name}`"
        />
        <FormError :error="form.errors.created_by_user" />
      </div>

      <div class="col-span-6">
        <label for="client" class="label">Klient</label>
        <Autocomplete 
          :id="(item) => item.id" 
          v-model="form.client" 
          :source="clients"
          :fields="['first_name', 'last_name', 'email']"
          :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
          :name="(item) => `${item.first_name} ${item.last_name}`"
        />
        <FormError :error="form.errors.client" />
      </div>

      <div class="col-span-3">
        <label for="title" class="label">Tytuł</label>
        <input id="title" v-model="form.title" type="text" class="input" />
        <FormError :error="form.errors.title" />
      </div>

      <div class="col-span-3">
        <label for="type" class="label">Rodzaj projektu</label>
        <DropdownList 
          :id="(item) => item.id" 
          v-model="form.type"
          :name="(item) => item.name"
          :source="types"
        />
        <FormError :error="form.errors.type" />
      </div>

      <div class="col-span-3">
        <label for="price" class="label">Koszt malowania</label>
        <input id="price" v-model="form.price" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.price" />
      </div>

      <div class="col-span-3">
        <label for="visualization" class="label">Koszt wizualizacji</label>
        <input id="visualization" v-model="form.visualization" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.visualization" />
      </div>
  
      <div class="col-span-3">
        <label for="date_start" class="label">Data rozpoczęcia</label>
        <input id="date_start" ref="date_start" v-model="form.date_start" type="text" class="input" />
        <FormError :error="form.errors.date_start" />
      </div>

      <div class="col-span-3">
        <label for="date_deadline" class="label">Data zakończenia</label>
        <input id="date_deadline" ref="date_deadline" v-model="form.date_deadline" type="text" class="input" />
        <FormError :error="form.errors.date_deadline" />
      </div>

      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi do projektu</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj projekt</button>
    </section>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/FormError.vue'
import Autocomplete from '@/Components/UI/Autocomplete.vue'
import DropdownList from '@/Components/UI/DropdownList.vue'
import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import datepicker from '@/Helpers/datepicker.js'

defineProps({
  users: {
    type: Array,
    required: true,
  },
  clients: {
    type: Array,
    required: true,
  },
  types: {
    type: Array,
    required: true,
  },
})
  
const form = useForm({
  created_by_user: null,
  client: null,
  title: null,
  type: null,
  price: null,
  date_start: null,
  date_deadline: null,
  remarks: null,
})
  
const date_start = ref(null)
const date_deadline = ref(null)
  
onMounted(() => {
  datepicker.create(date_start, null, (event) => form.date_start = event.target.value)
  datepicker.create(date_deadline, null, (event) => form.date_deadline = event.target.value)
})
  
const create = () => form.post(route('projects.store'))
</script>