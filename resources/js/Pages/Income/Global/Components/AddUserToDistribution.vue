<template>
  <div class="p-4">
    <h1 class="title">Wybierz osobę</h1>
    <p class="text-sm text-gray-400">Wpisz imię i nazwisko lub adres email osoby. <br /> Następnie wybierz z listy osobę, która zostanie dodana do podziału.</p>
    <Autocomplete 
      :id="(item) => item.id" 
      v-model:objectId="user_id"
      v-model:searchQuery="userSearchQuery"
      :source="users"
      :fields="['first_name', 'last_name', 'email']"
      :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
      :name="(item) => `${item.first_name} ${item.last_name}`"
      class="mt-4"
    />
    <button class="w-full btn-primary col-span-6 mt-4" type="button" @click="addUser">Dodaj osobę do podziału</button>
  </div>
</template>

<script setup>
import { inject, ref } from 'vue'
import Autocomplete from '@/Components/UI/Form/Autocomplete.vue'

const users = inject('users', [])
const user_id = ref(null)
const userSearchQuery = ref(null)

const addUser = () => {
  const user = users.value.find(u => u.id == parseInt(user_id.value))
  emit('created', user)
  userSearchQuery.value = null
  user_id.value = null
}

const emit = defineEmits(['created'])
</script>