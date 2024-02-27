<template>
  <button class="btn-action" @click="open">Zmień etap</button>
  <DialogWindow v-model:show="show" @close="close">
    test
  </DialogWindow>
</template>
  
<script setup>
import DialogWindow from '@/Components/UI/DialogWindow.vue'
import { ref } from 'vue'

defineProps({
  project: Object,
})

const show = ref(false)
const open = () => show.value = true
const close = () => show.value = false
  
</script>