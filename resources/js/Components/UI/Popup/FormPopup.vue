<template>
  <span v-if="label" class="text-sm text-gray-400 underline cursor-pointer hover:text-gray-500" @click="open">{{ label }}</span>
  <div v-else @click="open">
    <slot />
  </div>
  <DialogWindow v-model:show="show" @close="close">
    <component :is="form" @created="created" />
  </DialogWindow>
</template>

<script setup>
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
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
  
}

const open = () => {
  show.value = true
}

const created = (data) => {
  show.value = false

  if(page.props.inertia?.length){
    emit('form-action-created', page.props.inertia)
  }
  else {
    emit('form-action-created', data)
  }
}
</script>