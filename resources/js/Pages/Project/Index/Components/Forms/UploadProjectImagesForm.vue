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
          v-model:errors="uploadImagesErrors"
          @init="init"
        />
        <FormError :error="form.errors.images" />
      </div>
      <button 
        type="submit" 
        class="w-full btn-primary col-span-6 mt-4"
      >
        {{ caption(form.images.length) }}
      </button>
    </section>
  </form>
</template>

<script setup>
import UploadImages from '@/Components/UI/Form/UploadImages.vue'
import FormError from '@/Components/UI/Form/FormError.vue'
import { ref, watch } from 'vue'
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
  saved: Boolean,
})

const emit = defineEmits(['uploaded', 'init', 'update:saved'])

const form = useForm({
  type_id: props.typeId,
  images: props.project.images
    .filter(i => i.type_id === props.typeId)
    .map(i => i.file),
})

const projectImagesLength = form.images.length
const uploadImagesComponent = ref(null)
const uploadImagesErrors = ref({})

const init = () => {
  const images = props.project.images
    .filter(image => image.type_id === props.typeId)
    .map(image => `projects/${image.file}`)

  addImages(images, { type: 'local' })
  emit('init')
}

const saved = () => {
  if(form.images.length || projectImagesLength){
    form.post(route('projects.upload', { project: props.project.id }), {
      preserveScroll: true,
      onSuccess: () => { emit('uploaded'); emit('update:saved', true) },
    })
  }
  else{
    emit('uploaded') 
    emit('update:saved')
  }
}

watch(() => form.images, () => { emit('update:saved', false) })

const clearImages = () => uploadImagesComponent.value.clearImages()
const addImages = (images, options) => uploadImagesComponent.value.addImages(images, options)

defineExpose({
  clearImages,
  addImages,
})
</script>