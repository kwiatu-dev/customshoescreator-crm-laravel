<template>
  <form class="container mx-auto p-4" @submit.prevent="edit">
    <h1 class="title">Edytuj inwestycje</h1>
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
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Edytuj inwestycje</button>
    </section>
  </form>
</template>
      
<script setup>
import { defineAsyncComponent, onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const FormError = defineAsyncComponent(() => import('@/Components/UI/Form/FormError.vue'))
const Autocomplete = defineAsyncComponent(() => import('@/Components/UI/Form/Autocomplete.vue'))
  
const props = defineProps({
  investment: {
    type: Object,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
})
      
const form = useForm({
  title: props.investment.title ?? null,
  amount: props.investment.amount ?? null,
  date: props.investment.date ?? null,
  interest_rate: props.investment.interest_rate,
  remarks: props.investment.remarks ?? null,
  user_id: props.investment.user_id ?? null,
})
      
const date = ref(null)
const userSearchQuery = ref('')
      
onMounted(async () => {
  const { default: datepicker } = await import('@/Helpers/datepicker.js')
  datepicker.create(date, null, (event) => form.date = event.target.value)
})
      
const edit = () => form.put(route('investments.update', { investment: props.investment.id }))
</script>