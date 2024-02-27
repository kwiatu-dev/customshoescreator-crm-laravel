<template>
  <form class="container mx-auto p-4" @submit.prevent="create">
    <h1 class="title">{{ title }}</h1>
    <p class="text-sm text-gray-400">{{ description }}</p>
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
</template>

<script setup>
import UploadImages from '@/Components/UI/UploadImages.vue'
import FormError from '@/Components/UI/FormError.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  project: {
    type: Object,
    required: true,
  },
  title: {
    type: String,
    default: 'Dodaj wizualizacje (opcjonalne)',
  },
  description: {
    type: String,
    default: 'Wizualizacje możesz dodać teraz lub przed zakończeniem zlecenia.',
  },
})

const emit = defineEmits(['created'])

const form = useForm({
  visualization_images: [],
})

const uploadImagesComponent = ref(null)
const visualization_errors = ref({})
const visualization_processing = ref(false)

const clearUploadedImages = () => {
  if(uploadImagesComponent.value?.pond){
    //todo: usuwaj tylko pliki tymczasowe
    uploadImagesComponent.value.pond.removeFiles({ revert: true })
  }
}

const create = () => {
  //todo: dodaj tylko zdjęcia bez zmiany statusu
//   form.post(route('projects.start', { project: props.project.id }), {
//     preserveScroll: true,
//     onSuccess: () => emit('created'),
//   })

  emit('created')
}

defineExpose({
  clearUploadedImages,
})

//todo: uzupełnij zdjęciami, które już zostały dodane
//todo: ustawić aby FilePond mógł usuwać tylko zdjęcia z folderu tmp
</script>