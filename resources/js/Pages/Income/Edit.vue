<template>
  <form class="container mx-auto p-4" @submit.prevent="update">
    <h1 class="title">Edytuj przychód</h1>
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
        <FormPopup :form="AddUserToDistribution" label="Dodaj osobę do podziału" @form-action-created="onUserAddToDistribution" />
        <FormError :error="form.errors.distribution" />
      </div>

      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Edytuj przychód</button>
    </section>
  </form>
</template>
  
<script setup>
import { defineAsyncComponent, onMounted, ref, watch, provide, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const FormError = defineAsyncComponent(() => import('@/Components/UI/Form/FormError.vue'))
const SummerizeIncomeSection = defineAsyncComponent(() => import('@/Pages/Income/Global/Components/SummerizeIncomeFormSection.vue'))
const UserDistribution = defineAsyncComponent(() => import('@/Pages/Income/Global/Components/UserDistribution.vue'))
const FormPopup = defineAsyncComponent(() => import('@/Components/UI/Popup/FormPopup.vue'))
const AddUserToDistribution = defineAsyncComponent(() => import('@/Pages/Income/Global/Components/AddUserToDistribution.vue'))

const props = defineProps({
  income: {
    type: Object,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
})
  
const form = useForm({
  title: props.income.title,
  date: props.income.date,
  price: parseFloat(props.income.price),
  remarks: props.income.remarks,
  costs: parseInt(props.income.costs),
  distribution: typeof props.income.distribution === 'string' ? JSON.parse(props.income.distribution) : props.income.distribution,
})
  
const date = ref(null)
const usersIncluded = ref(props.users.filter(user => form.distribution?.[user.id]))

const filteredUsers = computed(() =>
  props.users.filter(user => !usersIncluded.value.includes(user)),
)

provide('users', filteredUsers)
  
onMounted(async () => {
  const { default: datepicker } = await import('@/Helpers/datepicker.js')
  datepicker.create(date, null, (event) => form.date = event.target.value)
})

const onUserAddToDistribution = (user) => {
  usersIncluded.value.push(user)
}

watch(() => form.costs, () => {
  if (form.costs >= 100) {
    form.distribution = null
  }
})
  
const update = () => form.put(route('incomes.update', { income: props.income.id }))
</script>