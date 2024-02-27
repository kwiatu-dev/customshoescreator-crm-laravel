<template>
  <file-pond 
    ref="pond"
    name="images"
    class-name="filepond"
    label-idle="<b>Kliknij, aby przesłać</b> lub przeciągnij i upuść (max 10)"
    allow-multiple="true"
    accepted-file-types="image/*"
    style-item-panel-aspect-ratio="1"
    max-file-size="8MB"
    label-max-file-size-exceeded="Plik jest za duży"
    label-max-file-size="Dozwolona wielkość pliku: {filesize}"
    max-files="10"
    force-revert="true"
    label-button-remove-item="Usuń"
    label-button-abort-item-load="Przerwij"
    label-button-retry-item-load="Spróbuj ponownie"
    label-button-abort-item-processing="Anuluj"
    label-button-undo-item-processing="Cofnij"
    label-button-retry-item-processing="Spróbuj ponownie"
    label-button-process-item="Przesyłanie"
    label-tap-to-undo="Kliknij, aby cofnąć"
    label-tap-to-retry="Kliknij, aby spróbować ponownie"
    label-tap-to-cancel="Kliknij, aby anulować"
    label-file-remove-error="Błąd podczas usuwania"
    label-file-processing-revert-error="Błąd podczas przywracania"
    label-file-processing-error="Błąd podczas przesyłania"
    label-file-processing-aborted="Przesyłanie anulowane"
    label-file-processing-complete="Przesyłanie zakończone"
    label-file-processing="Przesyłanie"
    label-file-load-error="Błąd poczas ładowania"
    label-file-loading="Ładowanie"
    label-invalid-field="Pole zawiera nieprawidłowe pliki"
    label-file-type-not-allowed="Niepoprawny typ pliku"
    file-validate-type-label-expected-types="Oczekiwane typy: {allTypes}"
    :server="{
      url: '',
      process: {
        url: route('filepond.store'),
        method: 'POST',
        onload: handleFilePondLoad,
        onerror: handleFilePondProcessError,
      },
      revert: handleFilePondRevert,
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
    }"
  />
  <FormError 
    v-for="(error, image_id) in errors" 
    :key="image_id" 
    :error="error.join('\n')"
  />
</template>

<script setup>
import FormError from '@/Components/UI/FormError.vue'
import vueFilePond from 'vue-filepond'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const FilePond = vueFilePond(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFileValidateSize)
const page = usePage()
const pond = ref(null)
const images_in_processing = []

const props = defineProps({
  images: Array,
  errors: Object,
  processing: Boolean,
})

const emit = defineEmits(['update:images', 'update:errors', 'update:processing'])

const handleFilePondLoad = (response) => {
  const data = JSON.parse(response)
  emit('update:images', [...props.images, data])

  return data.subfolder
}

const handleFilePondRevert = async (uniqueId, load, error) => {
  const index = props.images.findIndex(image => image.subfolder === uniqueId)
  const data = props.images[index]

  try{
    await axios.delete(
      route('filepond.destroy', { filepond: uniqueId }), 
      { data },
    )

    emit('update:images', props.images.filter((_, i) => i !== index))

    return true
  }
  catch(e){
    const image = pond.value.getFiles().find(image => image.serverId === uniqueId)
    emit('update:errors', { ...props.errors, ...{ [image.id]: [`Wystąpił błąd podczas usuwania zdjęcia: ${image.filename}`] } })
    error('ups')

    return false
  }
}

const handleFilePondProcessError = (error) => {
  const errorMessages = JSON.parse(error)?.images
  const files = pond.value.getFiles()
  const file = files[0]

  if(errorMessages && errorMessages?.length){
    emit('update:errors', { ...props.errors, ...{ [file.id]: errorMessages } })
  }
}

document.addEventListener('FilePond:removefile', (e) => {
  const image_id = e.detail.file.id

  if(props.errors.hasOwnProperty(image_id)){
    const errors = Object.keys(props.errors).reduce((acc, key) => {
      if(key !== image_id){
        acc[key] = props.errors[key]
      }

      return acc
    }, {})

    emit('update:errors', errors)
  }
})

document.addEventListener('FilePond:processfilestart', (e) => {
  const image_id = e.detail.file.id
  images_in_processing.push(image_id)
  emit('update:processing', images_in_processing.length !== 0)
})

document.addEventListener('FilePond:processfile', (e) => {
  const image_id = e.detail.file.id
  const index = images_in_processing.findIndex(id => id === image_id)
  images_in_processing.splice(index, 1)
  emit('update:processing', images_in_processing.length !== 0)
})

const csrfToken = computed(
  () => page.props.csrfToken,
)

defineExpose({
  pond,
})
</script>

<style>
.filepond--credits{
  @apply !hidden;
}

.filepond--item {
    width: calc(33% - 0.5em);
}

.filepond--drop-label{
  @apply cursor-pointer dark:text-gray-300 text-gray-500;
}

.filepond--drop-label > label{
  @apply cursor-pointer text-sm;
}

.filepond--panel .filepond--panel-root{
  @apply dark:bg-gray-500 bg-gray-100;
}

.filepond--panel .filepond--panel-root{
  @apply border dark:border-gray-300 border-gray-300;
}

.filepond--root{
  @apply mb-0;
}

.filepond--file-action-button{
  @apply cursor-pointer;
}

@media (max-width: 800px) {
    .filepond--item {
        width: calc(100%);
    }
}
</style>

