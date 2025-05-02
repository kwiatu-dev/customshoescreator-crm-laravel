<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">Dodaj wydatek</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-3">
        <label for="title" class="label">Tytuł</label>
        <input id="title" v-model="form.title" type="text" class="input" />
        <FormError :error="form.errors.title" />
      </div>

      <div class="col-span-3">
        <label for="date" class="label">Data</label>
        <input id="date" ref="date" v-model="form.date" type="text" class="input" autocomplete="off" />
        <FormError :error="form.errors.date" />
      </div>

      <div class="col-span-3">
        <label for="price" class="label">Kwota</label>
        <input id="price" v-model="form.price" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.price" />
      </div>

      <div class="col-span-3">
        <label for="shop_name" class="label">Nazwa sklepu</label>
        <input id="shop_name" v-model="form.shop_name" type="text" class="input" />
        <FormError :error="form.errors.shop_name" />
      </div>

      <div class="col-span-6">
        <label for="file" class="label">Załącznik (faktura)</label>
        <div class="flex items-center justify-center w-full">
          <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 input cursor-pointer">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
              <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
              </svg>
              <p class="mb-2 text-sm text-gray-500 dark:text-gray-300"><span class="font-semibold">Kliknij, aby przesłać</span> lub przeciągnij i upuść</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG lub PDF (MAX. 800x400px)</p>
            </div>
            <input id="dropzone-file" type="file" class="hidden" @input="file" />
          </label>
        </div> 
        <div>
          <div v-if="form.file" class="text-sm mt-1">
            Wprowadzony plik: <b>{{ form.file.name }}</b>
          </div>
        </div>
        <FormError :error="form.errors.file" />
      </div>

      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj wydatek</button>
    </section>
  </form>
</template>

<script setup>
import { defineAsyncComponent, onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const FormError = defineAsyncComponent(() => import('@/Components/UI/Form/FormError.vue'))

const form = useForm({
  title: null,
  date: null,
  price: null,
  shop_name: null,
  file: null,
})

const date = ref(null)

onMounted(async () => {
  const { default: datepicker } = await import('@/Helpers/datepicker.js')
  datepicker.create(date, null, (event) => form.date = event.target.value)
})

const file = (event) => {
  form.file = event.target.files[0]
}

const create = () => form.post(route('expenses.store'))
</script>