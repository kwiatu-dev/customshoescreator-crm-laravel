<template>
  <div>
    <div class="grid-cols-7 w-64 leading-9 today-btn clear-btn datepicker-cell today absolute datepicker-dropdown view-switch next-btn prev-btn hidden" />
    <div class="flex flex-nowrap items-center py-4">
      <div class="relative w-1/2">
        <input
          ref="start"
          type="text"
          placeholder="Od"
          class="input-filter-l w-full bg-gray-600 border-gray-500 placeholder:text-gray-400 font-normal text-sm text-gray-300"
        />
      </div>
      <div class="relative w-1/2">
        <input
          ref="end"
          type="text" 
          placeholder="Do"
          class="input-filter-r w-full bg-gray-600 border-gray-500 placeholder:text-gray-400 font-normal text-sm text-gray-300"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'

const start = ref(null)
const end = ref(null)

const props = defineProps({
  form: Object,
})

const form = reactive({
  ...props.form,
})

onMounted(() => {
  Datepicker.locales.pl = language.pl

  new Datepicker(start.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
  })
  
  start.value.addEventListener('changeDate', (event) => {
    form.date_start = event.target.value 
    emit('filters-update', form)
  })

  new Datepicker(end.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
  })

  end.value.addEventListener('changeDate', (event) => {
    form.date_end = event.target.value 
    emit('filters-update', form)
  })
})

const emit = defineEmits(['filters-update'])
</script>

<style scoped>
.grid-cols-7{
  grid-template-columns: repeat(7,minmax(0,1fr));
}
    
.w-64{
  width: 16rem;
}

.leading-9{
  line-height: 2.25rem;
}
</style>