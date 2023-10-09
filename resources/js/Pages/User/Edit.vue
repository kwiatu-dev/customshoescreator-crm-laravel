<template>
  <form class="container mx-auto p-4" @submit.prevent="update">
    <h1 class="title">Edytuj użytkownika</h1>
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
        <label for="costs" class="label">Koszty stałe</label>
        <input id="costs" v-model="form.costs" type="number" class="input" />
        <FormError :error="form.errors.costs" />
      </div>
  
      <div class="col-span-3">
        <label for="commission" class="label">Prowizja</label>
        <input id="commission" v-model="form.commission" type="number" class="input" />
        <FormError :error="form.errors.commission" />
      </div>

      <div class="col-span-6">
        <label for="distribution" class="label">Podział</label>
        <input id="distribution" v-model="percentage" type="range" class="w-full h-4 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" />
        <div class="w-full flex flex-row flex-nowrap justify-between">
          <span class="text-xs ml-1 select-none">Dla Filipa {{ filip }}%</span>
          <span class="text-xs mr-1 select-none">Dla Aleksandry {{ aleksandra }}%</span>
        </div>
        <FormError :error="form.errors.distribution" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Edytuj użytkownika</button>
    </section>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/FormError.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  user: Object,
})
  
const form = useForm({
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email,
  phone: props.user.phone,
  street: props.user.street,
  street_nr: props.user.street_nr,
  apartment_nr: props.user.apartment_nr,
  postcode: props.user.postcode,
  city: props.user.city,
  country: props.user.country,
  commission: props.user.commission,
  costs: props.user.costs,
  distribution: JSON.parse(props.user.distribution),
})

const distribution = form.distribution
const percentage = ref(distribution['1'])
const filip = computed(() => parseInt(percentage.value))
const aleksandra = computed(() => parseInt(100 - percentage.value))

const update = () => {
  
  form.distribution = JSON.stringify({1: filip.value, 2: aleksandra.value})
  form.put(route('user.update', {user: props.user.id}))
}
</script>