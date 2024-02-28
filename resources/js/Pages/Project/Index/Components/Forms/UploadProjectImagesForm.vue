<template>
  <form @submit.prevent="saved">
    <h1 class="title">
      {{ title }}
    </h1>
    <p class="text-sm text-gray-400">
      {{ description }}
    </p>
    <section class="mt-8 flex flex-col justify-center md:grid md:grid-cols-6 gap-4">
      <div class="col-span-6">
        <label class="label">
          {{ label }}
        </label>
        <UploadImages 
          ref="uploadImagesComponent" 
          v-model:images="form.images" 
          v-model:errors="uploadErrors" 
          v-model:processing="uploading"
        />
        <FormError :error="form.errors.images" />
      </div>
      <button 
        type="submit" 
        class="w-full btn-primary col-span-6 mt-4"
        :disabled="uploading"
      >
        {{ caption(form.images.length) }}
      </button>
      <FormError :error="saveError" class="-mt-4" />
    </section>
  </form>
</template>

<script setup>
import UploadImages from '@/Components/UI/Form/UploadImages.vue'
import FormError from '@/Components/UI/Form/FormError.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  project: {
    type: Object,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  caption: {
    type: Function,
    required: true,
  },
  typeId: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['saved'])

const form = useForm({
  type_id: props.typeId,
  images: [],
})

const uploadImagesComponent = ref(null)
const uploadErrors = ref({})
const saveError = ref(null)
const uploading = ref(false)

const clearImages = () => uploadImagesComponent.value.clearImages()
const addImages = (images) => uploadImagesComponent.value.addImages(images)

const saved = () => {
  form.post(route('projects.upload', { project: props.project.id }), {
    preserveScroll: true,
    onSuccess: () => { saveError.value = null; emit('saved') },
    onError: () => saveError.value = 'Wystąpił błąd podczas zapisywania!',
  })
}

defineExpose({
  clearImages,
  addImages,
})
</script>