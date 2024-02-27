<template>
  <button class="btn-action" @click="open">Rozpocznij</button>
  <DialogWindow v-model:show="show" @close="close">
    <form class="container mx-auto p-4" @submit.prevent="create">
      <h1 class="title">Dodaj wizualizacje (opcjonalne)</h1>
      <p class="text-sm text-gray-400">Wizualizacje możesz dodać teraz lub przed zakończeniem zlecenia.</p>
      <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
        <div class="col-span-6">
          <label class="label">Wizualizacje (zdjęcia)</label>
          <UploadImages ref="uploadImagesComponent" v-model:images="form.visualization_images" v-model:errors="visualization_errors" v-model:processing="visualization_processing" />
          <FormError :error="form.errors.visualization_images" />
        </div>
        <button 
          type="submit" 
          class="w-full btn-primary col-span-6 mt-4"
          :disabled="visualization_processing"
        >
          {{ form.visualization_images?.length === 0 ? 'Kontynuuj bez zdjęć' : 'Zapisz' }}
        </button>
      </section>
    </form>
  </DialogWindow>
</template>

<script setup>
import UploadImages from '@/Components/UI/UploadImages.vue'
import DialogWindow from '@/Components/UI/DialogWindow.vue'
import FormError from '@/Components/UI/FormError.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  project: Object,
})

const form = useForm({
  visualization_images: [],
})

const uploadImagesComponent = ref(null)
const show = ref(false)
const visualization_errors = ref({})
const visualization_processing = ref(false)

const open = () => {
  if(props.project.visualization == 0){
    create()
  }
  else{
    show.value = true
  }
}

const close = () => {
  if(uploadImagesComponent.value?.pond){
    uploadImagesComponent.value.pond.removeFiles({ revert: true })
  }
}

const create = () => {
  show.value = false

  form.post(route('projects.start', { project: props.project.id }), {
    preserveScroll: true,
  })
}
</script>