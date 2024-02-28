<template>
  <button class="btn-action" @click="open">Zmień etap</button>
  <DialogWindow v-model:show="show" @close="close">
    <div class="w-full p-4">
      <Stepper v-model:steps="steps" v-model:active="steps_active" v-model:focus="step_focus" class="mb-4" />
      <div v-show="step_focus === 1">
        <UploadVisualizationForm 
          ref="uploadVisualizationComponent" 
          :project="project" 
          title="Dodaj wizualizacje"
          description=""
          endpoint="projects.start"
          @created="onUploadVisualization"
        />
      </div>
      <div v-show="step_focus === 2">
        {{ visualization_images }}
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
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
import Stepper from '@/Components/UI/Form/Stepper.vue'
import UploadVisualizationForm from '@/Pages/Project/Index/Components/Forms/UploadVisualizationForm.vue'
import { ref } from 'vue'

const props = defineProps({
  project: Object,
})

const show = ref(false)
const steps = ref((props.project.visualization * 1) ? 4 : 3)
const steps_active = ref(Math.max(...props.project.images.map(image => image.type_id)))
const step_focus = ref(steps_active.value + 1)

const open = () => {
  show.value = true
  steps.value = (props.project.visualization * 1) ? 4 : 3
  steps_active.value = Math.max(...props.project.images.map(image => image.type_id))
  step_focus.value = steps_active.value + 1
  uploadVisualizationComponent.value.uploadImages(visualization_images.value)
}

const close = () => {
  show.value = false
  uploadVisualizationComponent.value.clearUploadedImages()
}

const uploadVisualizationComponent = ref(null)
const privateImageEndpoint = (catalog, file) => `private/files/${catalog}/${file}`
const visualization_images = ref(props.project.images.map(image => privateImageEndpoint('projects', image.file)))
const onUploadVisualization = () => step_focus.value++

//todo: nie dodawaj możliwości uzupełniania zdjęć wizualizacji jeżeli w projekcie cena wizualizacji jest 0
  
</script>