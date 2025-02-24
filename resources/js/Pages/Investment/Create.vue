<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">Dodaj inwestycje</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-6">
        <label for="user_id" class="label">Inwestor</label>
        <Autocomplete 
          :id="(item) => item.id" 
          v-model:objectId="form.user_id"
          v-model:searchQuery="userSearchQuery"
          :source="users"
          :fields="['first_name', 'last_name', 'email']"
          :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
          :name="(item) => `${item.first_name} ${item.last_name}`"
        />
        <FormError :error="form.errors.user_id" />
      </div>
      
      <div class="col-span-6">
        <label for="title" class="label">Tytuł</label>
        <input id="title" v-model="form.title" type="text" class="input" />
        <FormError :error="form.errors.title" />
      </div>
    
      <div class="col-span-2">
        <label for="date" class="label">Data</label>
        <input id="date" ref="date" v-model="form.date" type="text" class="input" autocomplete="off" />
        <FormError :error="form.errors.date" />
      </div>
    
      <div class="col-span-2">
        <label for="amount" class="label">Kwota</label>
        <input id="amount" v-model="form.amount" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.amount" />
      </div>

      <div class="col-span-2">
        <label for="interest_rate" class="label">Stopa procentowa</label>
        <input id="interest_rate" v-model="form.interest_rate" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.interest_rate" />
      </div>
  
      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>

      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj inwestycje</button>
    </section>
  </form>
</template>
    
<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'
import Autocomplete from '@/Components/UI/Form/Autocomplete.vue'

defineProps({
  users: {
    type: Array,
    required: true,
  },
})
    
const form = useForm({
  title: null,
  amount: null,
  date: null,
  interest_rate: null,
  remarks: null,
  user_id: null,
})
    
const date = ref(null)
const userSearchQuery = ref('')
    
onMounted(() => {
  Datepicker.locales.pl = language.pl
    
  new Datepicker(date.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
    format: 'yyyy-mm-dd',
    defaultViewDate: new Date(form.date_start ?? 'today'),
  })
      
  date.value.addEventListener('changeDate', (event) => {
    form.date = event.target.value 
  })
})
    
const create = () => form.post(route('investments.store'))
</script>