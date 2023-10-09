<template>
  <form class="container mx-auto p-4" @submit.prevent="login">
    <h1 class="title">Zaloguj się</h1>
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
      <div class="col-span-6 mt-4">
        <label class="relative inline-flex items-center cursor-pointer">
          <input v-model="form.remember" type="checkbox" class="sr-only peer" />
          <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600" />
          <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Zapamiętaj mnie na tym urządzeniu</span>
        </label>
      </div>
      <div class="col-span-6">
        <button class="w-full btn-primary col-span-6 mt-4" type="submit">Zaloguj</button>
      </div>
    </section>
  </form>
</template>

<script setup>
import FormError from '@/Components/UI/FormError.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: null,
  password: null,
  remember: null,
})

const login = () => form.post(route('login.store'))
</script>