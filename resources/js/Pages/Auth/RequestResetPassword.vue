<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">Prośba o zresetowanie hasła</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-6">
        <label for="email" class="label">Email</label>
        <input id="email" v-model="form.email" type="text" class="input" />
        <FormError :error="form.errors.email" />
      </div>
  
      <div class="col-span-6">
        <button class="w-full btn-primary col-span-6 mt-4" type="submit">Wyślij prośbę</button>
      </div>
    </section>
  </form>
</template>
    
<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import {useForm} from '@inertiajs/vue3'
  
const form = useForm({
  email: null,
})
    
const create = () => form.post(route('password.email'))
</script>