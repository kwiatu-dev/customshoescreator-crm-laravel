<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">Dodaj przychód</h1>
    <section class="mt-8 bg-gray-800 p-2 rounded-sm grid grid-cols-1 gap-2">
      <div>
        <span>Przychód: </span> <span class="font-medium">{{ form.price ? `${form.price} zł` : 'BRAK' }}</span>
      </div>
      <div>
        <span>Dochód: </span> <span class="font-medium">{{ form.price ? `${form.price * form.costs / 100 } zł` : 'BRAK' }}</span>
      </div>
      <div>
        <span>Koszty stałe: </span> <span class="font-medium">{{ form.costs ? `${form.costs}%` : 'BRAK' }}</span>
      </div>
      <div v-if="form.costs < 100 && typeof form.costs === 'number' && typeof form.price === 'number'">
        <div>Podział: </div>
        <ol class="!list-disc ml-4" style="list-style-type: disc;">
          <li v-for="([key, value], index) in Object.entries(form.distribution)" :key="key">
            <span>{{ admins.find(admin => admin.id == key).first_name }}:</span> <span class="font-medium">{{ ((form.price - (form.price * form.costs / 100) || 0) * value / 100).toFixed(2) }} zł</span>
          </li>
        </ol>
      </div>
      <div v-else>
        <span>Zarząd: </span> <span class="font-medium">BRAK</span>
      </div>
    </section>
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
        <AdminDistribution v-model="form.distribution" :distribution="form.distribution" :users="admins" />
        <FormError :error="form.errors.distribution" />
      </div>

      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Dodaj wydatek</button>
    </section>
  </form>
</template>
  
<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import { onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'
import AdminDistribution from '@/Components/UI/Form/AdminDistribution.vue'

const props = defineProps({
  admins: {
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
  
const create = () => form.post(route('incomes.store'))
</script>