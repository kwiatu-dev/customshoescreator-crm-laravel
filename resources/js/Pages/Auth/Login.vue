<template>
  <form class="w-1/2 mx-auto" @submit.prevent="login">
    <div>
      <label for="email" class="label">Email</label>
      <input id="email" v-model="form.email" type="text" class="input" />
      <FormError :error="form.errors.email" />
    </div>
    <div class="mt-4">
      <label for="password" class="label">Password</label>
      <input id="password" v-model="form.password" type="password" class="input" />
      <FormError :error="form.errors.password" />
    </div>
    <div class="mt-4">
      <button class="btn-primary w-full" type="submit">Login</button>

      <div class="mt-2 text-center">
        <Link :href="route('user-account.create')" class="text-sm text-gray-500">Need an account? Click here!</Link>
      </div>
    </div>
  </form>
</template>

<script setup>
import FormError from '@/Components/UI/FormError.vue'
import {useForm, Link} from '@inertiajs/vue3'

const form = useForm({
  email: null,
  password: null,
})

const login = () => form.post(route('login.store'))
</script>