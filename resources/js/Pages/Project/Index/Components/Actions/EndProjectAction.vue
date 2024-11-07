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
    @close="closeDialogWindow"
  >
    <div class="w-full p-4">
      <Stepper 
        ref="stepper"
        v-model:steps="steps" 
        v-model:active="step_active" 
        v-model:focus="step_focus" 
        class="mb-4"
        @changed="changedStepInStepper"
      />
      <div v-show="step_focus === 1">
        <UploadProjectImagesForm 
          ref="visualization"
          v-model:saved="isVisualizationSaved"
          :project="project"
          :type-id="1"
          title="Dodaj wizualizacje"
          description=""
          label="Wizualizacje (zdjęcia)"
          :caption="(some) => some ? 'Zapisz' : 'Zapisz'"
          @uploaded="onVisualizationSave"
        />
      </div>
      <div v-show="step_focus === 2">
        <UploadProjectImagesForm 
          ref="process"
          v-model:saved="isProcessSaved"
          :project="project"
          :type-id="2"
          title="Dodaj zdjęcia z procesu realizacji"
          description="Dodaj zdjęcia związane z procesrem realizacji projektu."
          label="Proces realizacji (zdjęcia)"
          :caption="(some) => some ? 'Zapisz' : 'Zapisz'"
          @uploaded="onProcessSave"
        />
      </div>
      <div v-show="step_focus === 3">
        <UploadProjectImagesForm 
          ref="final"
          v-model:saved="isFinalSaved"
          :project="project"
          :type-id="3"
          title="Dodaj zdjęcia końcowe"
          description="Dodaj zdjęcia prezentujące ukończony projekt."
          label="Prezentacja projektu (zdjęcia)"
          :caption="(some) => some ? 'Zapisz' : 'Zapisz'"
          @uploaded="onFinalSave"
        />
      </div>
      <div v-show="step_focus === 4">
        <h1 class="title">
          Podsumowanie projektu
        </h1>
        <p class="text-sm text-gray-400">
          Przed zakończeniem projektu upewnij się, że wszystkie dane zostały poprawnie uzupełnione.
        </p>
        <section class="mt-8">
          <!-- todo: wypisać tutaj dane do podsumowania np. ilość czasu spędzone nad projektem, zdjęcia, prowizje -->
        </section>
        <button 
          class="w-full btn-primary col-span-6 mt-4"
          @click="updateProjectStatus"
        >
          Zakończ projekt
        </button>
      </div>
    </div>
  </DialogWindow>
</template>
  
<script setup>
import UploadProjectImagesForm from '@/Pages/Project/Index/Components/Forms/UploadProjectImagesForm.vue'
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
import Stepper from '@/Components/UI/Form/Stepper.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  project: Object,
})

const stepper = ref(null)
const show = ref(false)
const steps = ref(4)
const step_active = ref(Math.max(0,...props.project.images.map(image => image.type_id)))
const step_focus = ref(step_active.value + 1)

const open = () => {
  show.value = true
}


const visualization = ref(null)
const isVisualizationSaved = ref(true)
const process = ref(null)
const isProcessSaved = ref(true)
const final = ref(null)
const isFinalSaved = ref(true)

const changedStepInStepper = (step_before, step_after) => {
  const showAlert = () => {
    const goto = confirm('Zmiany nie zostały zapisane! Czy na pewno chcesz kontynuować?')

    if (!goto) {
      step_focus.value = step_before
    }
  }

  if (step_before === 1 && !isVisualizationSaved.value) {
    showAlert()
  }

  if (step_before === 2 && !isProcessSaved.value) {
    showAlert()
  }

  if (step_before === 3 && !isFinalSaved.value) {
    showAlert()
  }
}

const closeDialogWindow = () => {
  if (!isVisualizationSaved.value || !isProcessSaved.value || !isFinalSaved.value) {
    show.value = !confirm('Zmiany nie zostały zapisane! Czy na pewno chcesz kontynuować?')
  }
}

const onVisualizationSave = () => {
  stepper.value.activate_step(1)
  stepper.value.next()
}

const onProcessSave = () => {
  stepper.value.activate_step(2)
  stepper.value.next()
}

const onFinalSave = () => {
  stepper.value.activate_step(3)
  stepper.value.next()
}

const updateProjectStatus = () => {
  const form = useForm({ status_id: 3 })

  form.post(route('projects.status', { project: props.project.id }), {
    onSuccess: () => { show.value = false },
    preserveScroll: true,
  })

  //todo: zwrócić błędy z backend po dodaniu walidacji: 
  //- jezeli jest ustawiona cena wizualizacji to musi zostac dodane conajmniej jedno zdjecie
  //- musza zostac przeslane zdjecia z procesu realizacji conajmniej 5
  //- musza zostac przeslane zdjecia z konca realizacji conajmniej 5
}
</script>