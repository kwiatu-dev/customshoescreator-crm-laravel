<template>
  <button 
    class="btn-action" 
    @click="open"
  >
    Zmień etap
  </button>
  <DialogWindow 
    v-if="show"
    v-model:show="show" 
    @close="close"
  >
    <div class="w-full p-4">
      <Stepper 
        v-model:steps="steps" 
        v-model:active="steps_active" 
        v-model:focus="step_focus" 
        class="mb-4"
      />
      <div v-show="step_focus === 1">
        <UploadProjectImagesForm 
          ref="visualization"
          :project="project"
          :type-id="1"
          title="Dodaj wizualizacje"
          description=""
          label="Wizualizacje (zdjęcia)"
          :caption="(some) => some ? 'Dalej' : 'Pomiń'"
          @saved="onVisualizationSave"
          @init="onVisualizationInit"
        />
      </div>
      <div v-show="step_focus === 2">
        <UploadProjectImagesForm 
          ref="process"
          :project="project"
          :type-id="2"
          title="Dodaj zdjęcia"
          description="Dodaj zdjęcia związane z procesrem realizacji projektu."
          label="Proces realizacji (zdjęcia)"
          :caption="(some) => some ? 'Dalej' : 'Pomiń'"
          @saved="onProcessSave"
        />
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
import UploadProjectImagesForm from '@/Pages/Project/Index/Components/Forms/UploadProjectImagesForm.vue'
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
import Stepper from '@/Components/UI/Form/Stepper.vue'
import { onMounted, onUpdated, ref } from 'vue'

const props = defineProps({
  project: Object,
})

const show = ref(false)
const steps = ref((props.project.visualization * 1) ? 4 : 3)
const steps_active = ref(Math.max(...props.project.images.map(image => image.type_id)))
const step_focus = ref(steps_active.value + 1)

const open = () => {
  show.value = true
}

const close = () => {

}

const source = (catalog, file) => `private/files/${catalog}/${file}`
const visualization = ref(null)
const process = ref(null)

const onVisualizationSave = () => {

}

const onVisualizationInit = () => {
  visualization.value.addImages(props.project.images.map(image => source('projects', image.file)), { type: 'local' })
}

const onProcessSave = () => {

}

//todo: po zapisaniu zmian sprawdzić po prostu, czy jakieś zdjęcie zniknęło, jeżeli tak to wtedy usunać, a jeżeli zostało dodane jakiegoś, którego nie ma na liście to dodać

</script>