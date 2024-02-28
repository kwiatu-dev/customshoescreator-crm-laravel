<template>
  <form class="container mx-auto p-4" @submit.prevent="update">
    <h1 class="title">Resetowanie hasła</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-6">
        <label for="email" class="label">Email</label>
        <input id="email" v-model="form.email" type="text" class="input" />
        <FormError :error="form.errors.email" />
      </div>

      <div class="col-span-6">
        <label for="password" class="label">Hasło</label>
        <input id="password" v-model="form.password" type="password" class="input" />
        <FormError :error="form.errors.password" />
      </div>

      <div class="col-span-6">
        <label for="password" class="label">Potwierdź hasło</label>
        <input id="password" v-model="form.password_confirmation" type="password" class="input" />
      </div>

      <div class="col-span-6">
        <button class="w-full btn-primary col-span-6 mt-4" type="submit">Resetuj hasło</button>
      </div>
    </section>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import {useForm} from '@inertiajs/vue3'

const props = defineProps({
  token: String,
})
  
const form = useForm({
  email: null,
  password: null,
  password_confirmation: null,
  token: props.token,
})
  
const update = () => form.post(route('password.update'))
</script>