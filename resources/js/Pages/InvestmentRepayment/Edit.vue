<template>
  <form class="container mx-auto p-4" @submit.prevent="edit">
    <h1 class="title">Edytuj zwrot inwestycji</h1>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-3">
        <label for="date" class="label">Data</label>
        <input id="date" ref="date" v-model="form.date" type="text" class="input" autocomplete="off" />
        <FormError :error="form.errors.date" />
      </div>
      
      <div class="col-span-3">
        <label for="repayment" class="label">Kwota</label>
        <input id="repayment" v-model="form.repayment" type="number" class="input" step="any" min="0" />
        <span>Do spłaty pozostało: {{ Number(investment.total - investment.total_repayment).toFixed(2) }} zł</span>
        <FormError :error="form.errors.repayment" />
      </div>
    
      <div class="col-span-6">
        <label for="remarks" class="label">Uwagi</label>
        <textarea id="remarks" v-model="form.remarks" class="input" rows="5" />
        <FormError :error="form.errors.remarks" />
      </div>
  
      <button type="submit" class="w-full btn-primary col-span-6 mt-4">Edytuj zwrot inwestycji</button>
    </section>
  </form>
</template>
  
<script setup>
import { defineAsyncComponent, onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const FormError = defineAsyncComponent(() => import('@/Components/UI/Form/FormError.vue'))
  
const props = defineProps({
  investment: {
    type: Object,
    required: true,
  },
  repayment: {
    type: Object,
    required: true,
  },
})
      
const form = useForm({
  repayment: props.repayment.repayment,
  date: props.repayment.date,
  remarks: props.repayment.remarks,
})
      
const date = ref(null)
      
onMounted(async () => {
  const { default: datepicker } = await import('@/Helpers/datepicker.js')
  datepicker.create(date, null, (event) => form.date = event.target.value)
})
      
const edit = () => form.put(route('repayments.update', { investment: props.investment.id, repayment: props.repayment.id }))
</script>