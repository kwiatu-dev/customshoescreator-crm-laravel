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
        <section class="mt-8 flex flex-col gap-8">
          <Box class="!border-indigo-500 dark:!bg-gray-900 !bg-gray-200">
            <template #header>
              Terminy
            </template>
            <div>
              <div>
                <span>Data rozpoczęcia:</span>
                {{ project.start }}
              </div>
              <div>
                <span>Data zakończenia:</span>
                {{ today }} (aktualna data)
              </div>
              <div>
                <span>Czas realizacji:</span>
                {{ useProjectDurationTime(project.start, today).durationDays }} dni
              </div>
            </div>
          </Box>
          <Box class="!border-indigo-500 dark:!bg-gray-900 !bg-gray-200">
            <template #header>
              Koszty i prowizje
            </template>
            <div>
              <span>Koszt wizualizacji: </span>
              {{ project.visualization }} zł
            </div>
            <div>
              <span>Koszt projektu: </span>
              {{ project.price }} zł
            </div>
            <div>
              <span>Koszty stałe: </span>
              {{ project.costs }}%
            </div>
            <div>
              <span>Prowizja: </span>
              {{ project.commission }}%
            </div>
          </Box>
          <Box class="!border-indigo-500 dark:!bg-gray-900 !bg-gray-200">
            <template #header>
              Rozliczenie
            </template>
            <div>
              <span>Dochód organizacji: </span>
              {{ useProjectCosts(project).organizationProfit }} zł
            </div>
            <div>
              <span>Zysk wykonawcy projektu: </span>
              {{ useProjectCosts(project).employeeProfit }} zł (wizualizacja wliczona)
            </div>
            <div>
              <span>Zysk zarządu: </span>
              {{ useProjectCosts(project).managementProfit }} zł
            </div>
          </Box>
          <Box class="!border-indigo-500 dark:!bg-gray-900 !bg-gray-200">
            <template #header>
              Zdjęcia
            </template>
            <div class="flex flex-col gap-4">
              <div>
                <div>Inspiracje</div>
                <PhotoGrid :photos="project.images.filter(image => image.type_id === 4)" />
              </div>
              <div>
                <div>Wizualizacje komputerowe</div>
                <PhotoGrid :photos="project.images.filter(image => image.type_id === 1)" />
              </div>
              <div>
                <div>Proces realizacji</div>
                <PhotoGrid :photos="project.images.filter(image => image.type_id === 2)" />
              </div>
              <div>
                <div>Zdjęcia końcowe</div>
                <PhotoGrid :photos="project.images.filter(image => image.type_id === 3)" />
              </div>
            </div>
          </Box>
        </section>
        <button 
          class="w-full btn-primary col-span-6 mt-4 mb-2"
          @click="updateProjectStatus"
        >
          Zakończ projekt
        </button>
        <FormError v-for="(error, index) in form.errors" :key="index" :error="error" />
      </div>
    </div>
  </DialogWindow>
</template>
  
<script setup>
import UploadProjectImagesForm from '@/Pages/Project/Index/Components/Forms/UploadProjectImagesForm.vue'
import PhotoGrid from '@/Pages/Project/Show/Components/PhotoGrid.vue'
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
import Stepper from '@/Components/UI/Form/Stepper.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { useProjectDurationTime } from '@/Composables/useProjectDurationTime'
import Box from '@/Components/UI/List/Box.vue'
import { useProjectCosts } from '@/Composables/useProjectCosts'
import FormError from '@/Components/UI/Form/FormError.vue'

const props = defineProps({
  project: Object,
})

const stepper = ref(null)
const show = ref(false)
const steps = ref(4)
const step_active = ref(0)
const step_focus = ref(1)

const open = () => {
  show.value = true
}

const form = useForm({ status_id: 3 })
const visualization = ref(null)
const isVisualizationSaved = ref(true)
const process = ref(null)
const isProcessSaved = ref(true)
const final = ref(null)
const isFinalSaved = ref(true)
const today = dayjs().format('YYYY-MM-DD')

const changedStepInStepper = (step_before, step_after) => {
  const showAlert = () => {
    const goto = confirm('Zmiany nie zostały zapisane! Czy na pewno chcesz kontynuować?')

    if (!goto) {
      step_focus.value = step_before
    }
  }

  if (step_before === 1 && step_after !== 1 && !isVisualizationSaved.value) {
    showAlert()
  }

  if (step_before === 2 && step_after !== 2 && !isProcessSaved.value) {
    showAlert()
  }

  if (step_before === 3 && step_after !== 3 && !isFinalSaved.value) {
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
  form.post(route('projects.status', { project: props.project.id }), {
    onSuccess: () => { show.value = false },
    preserveScroll: true,
  })
}
</script>