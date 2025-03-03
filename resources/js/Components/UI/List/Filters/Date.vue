<template>
  <div>
    <div class="focused grid-cols-7 w-64 leading-9 today-btn clear-btn datepicker-cell today absolute datepicker-dropdown view-switch next-btn prev-btn hidden" />
    <div class="flex flex-nowrap items-center py-4">
      <div class="relative w-1/2">
        <input
          ref="start"
          type="text"
          placeholder="Od"
          class="filter-input rounded-r-none"
        />
      </div>
      <div class="relative w-1/2">
        <input
          ref="end"
          type="text" 
          placeholder="Do"
          class="filter-input rounded-l-none"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, reactive, watch } from 'vue'
import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'

const start = ref(null)
const end = ref(null)
let pickerStart = ref(null)
let pickerEnd = ref(null)

const props = defineProps({
  form: Object,
  column: String,
})

const date_start = `${props.column}_start`
const date_end = `${props.column}_end`

const form = reactive({
  [date_start]: props.form[date_start] ?? null,
  [date_end]: props.form[date_end] ?? null,
})

onMounted(() => {
  Datepicker.locales.pl = language.pl

  pickerStart.value = new Datepicker(start.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
    format: 'yyyy-mm-dd',
    defaultViewDate: new Date(form[date_start] ?? 'today'),
  })

  start.value.value = form[date_start] ?? null
  pickerStart.value.update()
  
  start.value.addEventListener('changeDate', (event) => {
    form[date_start] = event.target.value 
    emit('filters-update', form)
  })

  pickerEnd.value = new Datepicker(end.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
    format: 'yyyy-mm-dd',
    defaultViewDate: new Date(form[date_end] ?? 'today'),
  })

  end.value.value = form[date_end] ?? null
  pickerEnd.value.update()

  end.value.addEventListener('changeDate', (event) => {
    form[date_end] = event.target.value 
    emit('filters-update', form)
  })
})

const emit = defineEmits(['filters-update'])

watch(props.form, () => {
  form[date_start] = props.form[date_start] ?? null
  form[date_end] = props.form[date_end] ?? null
  start.value.value = props.form[date_start] ?? null
  end.value.value = props.form[date_end] ?? null
  pickerStart.value.update()
  pickerEnd.value.update()
})

defineExpose({
  pickerStart,
  pickerEnd,
})
</script>