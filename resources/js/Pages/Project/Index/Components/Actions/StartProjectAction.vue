<template>
  <button 
    class="btn-action" 
    @click="open"
  >
    Rozpocznij
  </button>
  <DialogWindow 
    v-if="show" 
    v-model:show="show" 
    @close="closeDialogWindow"
  >
    <UploadProjectImagesForm 
      ref="form"
      v-model:saved="isSaved"
      :project="project"
      :type-id="1"
      title="Dodaj wizualizacje (opcjonalnie)"
      description="Wizualizacje możesz dodać teraz lub przed zakończeniem zlecenia."
      label="Wizualizacje (zdjęcia)"
      :caption="(some) => some ? 'Zapisz' : 'Pomiń'"
      class="p-4"
      @uploaded="updateProjectStatus"
    />
  </DialogWindow>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import DialogWindow from '@/Components/UI/Popup/Popup.vue'
import UploadProjectImagesForm from '@/Pages/Project/Index/Components/Forms/UploadProjectImagesForm.vue'

const props = defineProps({
  project: Object,
})

const show = ref(false)
const isSaved = ref(true)

const open = async () => {
  if(props.project.visualization * 1 > 0){
    show.value = true
  }
  else{
    await updateProjectStatus()
  }
}

const closeDialogWindow = () => {
  if (!isSaved.value) {
    show.value = !confirm('Zmiany nie zostały zapisane! Czy na pewno chcesz kontynuować?')
  }
}

const updateProjectStatus = async () => {
  const form = useForm({ status_id: 2 }) 

  form.post(route('projects.status', { project: props.project.id }), {
    onSuccess: () => { show.value = false },
    preserveScroll: true,
  })
}
</script>