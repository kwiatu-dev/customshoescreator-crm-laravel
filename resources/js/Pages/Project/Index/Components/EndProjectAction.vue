<template>
  <button class="btn-action" @click="open">Zmień etap</button>
  <DialogWindow v-model:show="show" @close="close">
    <div class="p-4 pb-6 overflow-hidden">
      <Stepper v-model:steps="steps" v-model:active="steps_active" v-model:focus="step_focus" />
      <div v-show="step_focus === 1">
        <UploadVisualization 
          ref="uploadVisualizationComponent" 
          :project="project" 
          title="Dodaj wizualizacje"
          description=""
          @created="onUploadVisualization"
        />
      </div>
      <div v-show="step_focus === 2">
        test 2
      </div>
      <div v-show="step_focus === 3">
        test 3
      </div>
      <div v-show="step_focus === 4">
        test 4
      </div>
    </div>
  </DialogWindow>
</template>
  
<script setup>
import DialogWindow from '@/Components/UI/DialogWindow.vue'
import Stepper from '@/Pages/Project/Index/Components/Stepper.vue'
import UploadVisualization from '@/Pages/Project/Index/Components/UploadVisualization.vue'
import { ref } from 'vue'

defineProps({
  project: Object,
})

const show = ref(false)
const steps = ref(4)
const steps_active = ref(2)
const step_focus = ref(2)
const open = () => show.value = true

const close = () => {
  show.value = false
  uploadVisualizationComponent.value.clearUploadedImages()
}

const uploadVisualizationComponent = ref(null)

const onUploadVisualization = () => step_focus.value++

//todo: nie dodawaj możliwości uzupełniania zdjęć wizualizacji jeżeli w projekcie cena wizualizacji jest 0
  
</script>