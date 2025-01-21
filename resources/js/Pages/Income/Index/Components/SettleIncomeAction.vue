<template>
  <button 
    class="btn-action" 
    @click="open"
  >
    Rozlicz
  </button>
  <DialogWindow 
    v-if="show"
    v-model:show="show" 
    @close="close"
  >
    <div class="p-4">
      <h1 class="title">Podsumowanie przychodu</h1>
      <p class="text-sm text-gray-400">Zapoznaj się z podsumowaniem przychodu przed podjęciem decyzji o rozliczeniu.</p>
      <SummerizeIncome :income="income" class="mt-4" />
      <button type="button" class="w-full btn-primary col-span-6 mt-4" @click="settle">Rozlicz przychód</button>
    </div>
  </DialogWindow>
</template>

<script setup>
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
import { ref, onMounted } from 'vue'
import SummerizeIncome from '../../Global/Components/SummerizeIncome.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  income: {
    type: Object,
    required: true,
  },
})

const show = ref(false)

onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search)
  const id = urlParams.get('settle')

  if (id && id == props.income.id) {
    show.value = true
  }
})

const open = () => {
  const currentParams = new URLSearchParams(window.location.search)
  currentParams.set('settle', props.income.id) 
  const newUrl = `${window.location.origin}${window.location.pathname}?${currentParams.toString()}`

  router.get(
    newUrl, 
    {},
    { replace: true, preserveScroll: true }, 
  )

  show.value = true
}

const close = () => {
  const currentParams = new URLSearchParams(window.location.search)
  currentParams.delete('settle')
  const newUrl = `${window.location.origin}${window.location.pathname}?${currentParams.toString()}`

  router.get(
    newUrl,
    {},
    { replace: true, preserveScroll: true },
  )

  show.value = false
}

const settle = () => {
  router.put(route('incomes.settle', { income: props.income.id }), 
    {},
    {
      onFinish: () => show.value = false,
    })
}

</script>