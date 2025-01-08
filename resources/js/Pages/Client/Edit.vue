<template>
  <form class="container mx-auto p-4" @submit.prevent="update">
    <h1 class="title">Edytuj klienta</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-3">
        <label for="first_name" class="label">Imię</label>
        <input id="first_name" v-model="form.first_name" type="text" class="input" />
        <FormError :error="form.errors.first_name" />
      </div>
  
      <div class="col-span-3">
        <label for="last_name" class="label">Nazwisko</label>
        <input id="last_name" v-model="form.last_name" type="text" class="input" />
        <FormError :error="form.errors.last_name" />
      </div>
  
      <div class="col-span-3">
        <label for="email" class="label">Email</label>
        <input id="email" v-model="form.email" type="text" class="input" />
        <FormError :error="form.errors.email" />
      </div>
  
      <div class="col-span-3">
        <label for="phone" class="label">Telefon</label>
        <input id="phone" v-model="form.phone" type="text" class="input" />
        <FormError :error="form.errors.phone" />
      </div>
  
      <div class="col-span-2">
        <label for="street" class="label">Ulica</label>
        <input id="street" v-model="form.street" type="text" class="input" />
        <FormError :error="form.errors.street" />
      </div>
  
      <div class="col-span-2">
        <label for="street_nr" class="label">Numer ulicy</label>
        <input id="street_nr" v-model="form.street_nr" type="text" class="input" />
        <FormError :error="form.errors.street_nr" />
      </div>
  
      <div class="col-span-2">
        <label for="apartment_nr" class="label">Numer mieszkania</label>
        <input id="apartment_nr" v-model="form.apartment_nr" type="text" class="input" />
        <FormError :error="form.errors.apartment_nr" />
      </div>
  
      <div class="col-span-2">
        <label for="postcode" class="label">Kod pocztowy</label>
        <input id="postcode" v-model="form.postcode" type="text" class="input" />
        <FormError :error="form.errors.postcode" />
      </div>
  
      <div class="col-span-2">
        <label for="city" class="label">Miasto</label>
        <input id="city" v-model="form.city" type="text" class="input" />
        <FormError :error="form.errors.city" />
      </div>
  
      <div class="col-span-2">
        <label for="country" class="label">Kraj</label>
        <input id="country" v-model="form.country" type="text" class="input" />
        <FormError :error="form.errors.country" />
      </div>
  
      <div class="col-span-3">
        <label for="username" class="label">Nazwa użytkownika</label>
        <input id="username" v-model="form.username" type="text" class="input" />
        <FormError :error="form.errors.username" />
      </div>
  
      <div class="col-span-3">
        <label for="conversion_source_id" class="label">Źródło konwersji</label>
        <DropdownList 
          :id="(item) => item.id" 
          v-model.number="form.conversion_source_id"
          :name="(item) => item.name"
          :source="conversionSources"
        />
        <FormError :error="form.errors.conversion_source_id" />
      </div>
  
      <div v-if="form.username" class="col-span-6">
        <label for="social_link" class="label">Link do profilu</label>
        <input id="social_link" v-model="form.social_link" type="text" class="input" />
        <FormError :error="form.errors.social_link" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Edytuj klienta</button>
    </section>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import DropdownList from '@/Components/UI/Form/DropdownList.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  client: {
    type: Object,
    required: true,
  },
  conversionSources: {
    type: Array,
    required: true,
  },
})
  
const form = useForm({
  first_name: props.client.first_name,
  last_name: props.client.last_name,
  email: props.client.email,
  phone: props.client.phone,
  street: props.client.street,
  street_nr: props.client.street_nr,
  apartment_nr: props.client.apartment_nr,
  postcode: props.client.postcode,
  city: props.client.city,
  country: props.client.country,
  username: props.client.username,
  social_link: props.client.social_link,
  conversion_source_id: props.client.conversion_source_id,
})
  
const update = () => form.put(route('client.update', {client: props.client.id}))
</script>