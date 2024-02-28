<template>
  <button class="btn-action" :disabled="failed" @click="open">Rozpocznij</button>
  <DialogWindow 
    v-if="show" 
    v-model:show="show" 
  >
    <UploadProjectImagesForm 
      ref="form"
      :project="project"
      :type-id="1"
      title="Dodaj wizualizacje (opcjonalnie)"
      description="Wizualizacje możesz dodać teraz lub przed zakończeniem zlecenia."
      label="Wizualizacje (zdjęcia)"
      :caption="(some) => some ? 'Zapisz' : 'Pomiń'"
      class="p-4"
      @saved="uploaded"
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
const failed = ref(false)

const open = async () => {
  if(props.project.visualization * 1 > 0){
    show.value = true
  }
  else{
    await updateStatus()
  }
}

const updateStatus = async () => {
  const form = useForm({ status_id: 2 })

  form.post(route('projects.status', { project: props.project.id }), {
    preserveScroll: true,
  })
}

const uploaded = async () => {
  show.value = false
  await updateStatus()
}
</script>