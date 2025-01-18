<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">Dodaj przychód</h1>
    <SummerizeIncomeSection :form="form" :users="users" />
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
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
        <label for="price" class="label">Kwota</label>
        <input id="price" v-model="form.price" type="number" class="input" step="any" min="0" />
        <FormError :error="form.errors.price" />
      </div>

      <div class="col-span-2">
        <label for="costs" class="label">Koszty stałe</label>
        <input id="costs" v-model="form.costs" type="number" class="input" />
        <FormError :error="form.errors.costs" />
      </div>

      <div v-if="form.costs < 100 && typeof form.costs === 'number'" class="col-span-6">
        <label for="distribution" class="label">Podział</label>
        <UserDistribution v-model="form.distribution" :distribution="form.distribution" :users="usersIncluded" />
        <FormError :error="form.errors.distribution" />
        <FormPopup :form="AddUserToDistribution" label="Dodaj osobę do podziału" @form-action-created="onUserAddToDistribution" />
      </div>

      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj przychód</button>
    </section>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import { onMounted, ref, watch, computed, provide } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'
import SummerizeIncomeSection from '@/Pages/Income/Global/Components/SummerizeIncomeFormSection.vue'
import UserDistribution from '@/Pages/Income/Global/Components/UserDistribution.vue'
import FormPopup from '@/Components/UI/Popup/FormPopup.vue'
import AddUserToDistribution from '@/Pages/Income/Global/Components/AddUserToDistribution.vue'

const props = defineProps({
  users: {
    type: Array,
    required: true,
  },
})
  
const form = useForm({
  title: null,
  date: null,
  price: null,
  remarks: null,
  costs: null,
  distribution: null,
})
  
const date = ref(null)
const usersIncluded = ref(props.users.filter(user => user.is_admin == true))

const filteredUsers = computed(() =>
  props.users.filter(user => !usersIncluded.value.includes(user)),
)

provide('users', filteredUsers)
  
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

watch(() => form.costs, () => {
  if (form.costs >= 100) {
    form.distribution = null
  }
})

const onUserAddToDistribution = (user) => {
  usersIncluded.value.push(user)
}
  
const create = () => form.post(route('incomes.store'))
</script>