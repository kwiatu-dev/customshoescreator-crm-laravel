<template>
  <span class="text-sm text-gray-400 underline cursor-pointer" @click="open">{{ label }}</span>
  <div class="fixed w-full h-full bg-gray-950 top-0 left-0 opacity-90 z-20" :class="[show ? 'block' : 'hidden']" />
  <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-4/5 h-4/5 z-20 bg-gray-800 overflow-y-auto" :class="[show ? 'block' : 'hidden']">
    <div class="w-full flex flex-col">
      <div class="w-full bg-gray-700 p-4 text-sm">
        <div class="w-full flex flex-row justify-between items-center">
          <span>Okno dialogowe</span>
          <span class="text-xl cursor-pointer p-2" @click="close">x</span>
        </div>
      </div>
      <component :is="form" @created="created" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

defineProps({
  form: {
    type: Object,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
})

const page = usePage()
const emit = defineEmits(['form-action-created'])
const show = ref(false)

const close = () => {
  show.value = false
}

const open = () => {
  show.value = true
}

const created = () => {
  close()
  
  if(page.props.inertia){
    emit('form-action-created', page.props.inertia)
  }
}
</script>