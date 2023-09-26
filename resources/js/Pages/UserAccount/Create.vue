<template>
  <form class="w-1/2 mx-auto" @submit.prevent="register">
    <div>
      <label for="name" class="label">Your name</label>
      <input id="name" v-model="form.name" type="text" class="input" />
      <FormError :error="form.errors.name" />
    </div>
    <div class="mt-4">
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
      <label for="password_confirmation" class="label">Confirm password</label>
      <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="input" />
    </div>
    <div class="mt-4">
      <button class="btn-primary w-full" type="submit">Create account</button>

      <div class="mt-2 text-center">
        <Link :href="route('login')" class="text-sm text-gray-500">Already have an account? Click here!</Link>
      </div>
    </div>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/FormError.vue'
import {useForm, Link} from '@inertiajs/vue3'
  
const form = useForm({
  name: null,
  email: null,
  password: null,
  password_confirmation: null,
  
})
  
const register = () => form.post(route('user-account.store'))
</script>