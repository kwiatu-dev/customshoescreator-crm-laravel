<template>
  <file-pond 
    ref="pond"
    name="images"
    class-name="filepond"
    label-idle="<b>Kliknij, aby przesłać</b> lub przeciągnij i upuść"
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
const images = ref([])
const errors = ref({})

defineProps({
  modelValue: Array,
  imagesErrors: Object,
})

const emit = defineEmits(['update:modelValue', 'update:imagesErrors'])

const handleFilePondLoad = (response) => {
  const data = JSON.parse(response)
  images.value.push(data)
  emit('update:modelValue', images.value)

  return data.subfolder
}

const handleFilePondRevert = async (uniqueId, load, error) => {
  const index = images.value.findIndex(image => image.subfolder === uniqueId)
  const data = images.value[index]

  if(data){
    try{
      await axios.delete(
        route('filepond.destroy', { filepond: uniqueId }), 
        { data },
      )

      images.value.splice(index, 1)
      emit('update:modelValue', images.value)
      return true
    }
    catch(e){
      const image = pond.value.getFiles().find(image => image.serverId === uniqueId)
      errors.value[image.id] = [`Wystąpił błąd podczas usuwania zdjęcia: ${image.filename}`]
      emit('update:imagesErrors', errors.value)
      error('ups')
      return false
    }
  }
}

const handleFilePondProcessError = (error) => {
  const errorMessages = JSON.parse(error)?.images
  const files = pond.value.getFiles()
  const file = files[0]

  if(errorMessages && errorMessages?.length){
    errors.value[file.id] = errorMessages
    emit('update:imagesErrors', errors.value)
  }
}

document.addEventListener('FilePond:removefile', (e) => {
  const image_id = e.detail.file.id

  if(errors.value.hasOwnProperty(image_id)){
    delete errors.value[image_id]
    emit('update:imagesErrors', errors.value)
  }
})

const csrfToken = computed(
  () => page.props.csrfToken,
)
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

