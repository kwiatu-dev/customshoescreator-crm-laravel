<template>
  <div class="flex flex-row flex-nowrap justify-end items-center gap-2">
    <div 
      class="col-span-2 text-center dark:text-gray-400" 
      style="font-size: 10px;"
    >
      od
    </div>
    <input
      id="from" 
      ref="from" 
      v-model="value.from" 
      type="text"
      style="font-size: 10px"
      class="col-span-3 text-center border border-gray-950 border-solid rounded-md font-medium px-0 py-0 bg-gray-100 dark:bg-gray-600 dark:border-gray-800 cursor-pointer dark:text-gray-50"
    />
    <div 
      class="col-span-2 text-center dark:text-gray-400" 
      style="font-size: 10px;"
    >
      do
    </div>
    <input
      id="to" 
      ref="to" 
      v-model="value.to" 
      type="text"
      style="font-size: 10px"
      class="col-span-3 text-center border border-gray-950 border-solid rounded-md font-medium px-0 py-0 bg-gray-100 dark:bg-gray-600 dark:border-gray-800 cursor-pointer dark:text-gray-50"
    />
  </div>
</template>

<script setup>
import { watch, ref, onMounted } from 'vue'
import datepicker from '@/Helpers/datepicker.js'
import dayjs from 'dayjs'

const props = defineProps({
  modelValue: {
    type: [Object, null],
    required: true,
  },
})

const value = ref(
  props.modelValue && props.modelValue.from && props.modelValue.to
    ? { ...props.modelValue }
    : {
      from: dayjs().format('YYYY-MM'),
      to: dayjs().format('YYYY-MM'),
    },
)

const from = ref(null)
const to = ref(null)
let pickerFrom = null, pickerTo = null

const updatePickers = () => {
  if (pickerFrom) {
    pickerFrom.setDate(dayjs(value.value.from, 'YYYY-MM').toDate())
  }
  if (pickerTo) {
    pickerTo.setDate(dayjs(value.value.to, 'YYYY-MM').toDate())
  }
}

onMounted(async () => {
  pickerFrom = datepicker.range(from, null, (event) => value.value.from = event.target.value, 'yyyy-mm')
  pickerTo = datepicker.range(to, null, (event) => value.value.to = event.target.value, 'yyyy-mm')
})

watch(() => value.value.from, (newFrom) => {
  const fromDate = dayjs(newFrom, 'YYYY-MM')
  const toDate = dayjs(value.value.to, 'YYYY-MM')

  if (fromDate.isAfter(toDate)) {
    value.value.to = fromDate.add(1, 'month').format('YYYY-MM')
  }

  emit('update:modelValue', value.value)
  updatePickers()
})

watch(() => value.value.to, (newTo) => {
  const fromDate = dayjs(value.value.from, 'YYYY-MM')
  const toDate = dayjs(newTo, 'YYYY-MM')

  if (fromDate.isAfter(toDate)) {
    value.value.from = toDate.subtract(1, 'month').format('YYYY-MM')
  }

  emit('update:modelValue', value.value)
  updatePickers()
})

const emit = defineEmits(['update:modelValue'])
</script>

<style scoped>
input {
  transition: all 0.2s ease-in-out;
}

input:hover {
  @apply bg-gray-200 dark:bg-gray-700; /* Jasnoszary kolor tła */
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Delikatny cień */ /* Subtelne powiększenie */
}
</style>