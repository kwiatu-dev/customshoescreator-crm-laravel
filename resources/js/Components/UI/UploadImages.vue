<template>
  <file-pond 
    ref="pond"
    name="images"
    class-name="filepond"
    label-idle="<b>Kliknij, aby przesłać</b> lub przeciągnij i upuść (JPEG, JPG, PNG)"
    allow-multiple="true"
    accepted-file-types="image/jpeg, image/png, image/jpg"
    style-item-panel-aspect-ratio="1"
    max-files="10"
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
    :server="{
      url: '',
      process: {
        url: '/filepond',
        method: 'POST',
        onload: handleFilePondLoad,
        onerror: handleFilePondProcessError,
      },
      revert: handleFilePondRevert,
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    }"
  />
  <FormError :error="errors" />
</template>

<script setup>
import FormError from '@/Components/UI/FormError.vue'
import vueFilePond from 'vue-filepond'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const FilePond = vueFilePond(FilePondPluginImagePreview)
const page = usePage()
const pond = ref(null)
const images = ref([])
const errors = ref(null)

defineProps({
  modelValue: Array,
})

const emit = defineEmits(['update:modelValue'])

const handleFilePondLoad = (uniqueId) => {
  images.value.push(uniqueId)
  emit('update:modelValue', images.value)
  errors.value = null
  return uniqueId
}

const handleFilePondRevert = (uniqueId, load, error) => {
  images.value = images.value.filter((image) => image !== uniqueId)
  errors.value = null
  emit('update:modelValue', images.value)
  router.delete(`/filepond/${uniqueId}`)
}

const handleFilePondProcessError = (error, file, status) => {
  const errorMessages = JSON.parse(error)?.images

  if(errorMessages && errorMessages?.length){
    errors.value = errorMessages.join('\n')
  }
}

document.addEventListener('FilePond:removefile', (e) => {
  errors.value = null
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
  @apply cursor-pointer dark:text-gray-300;
}

.filepond--drop-label > label{
  @apply cursor-pointer;
}

.filepond--panel .filepond--panel-root{
  @apply dark:bg-gray-500;
}

.filepond--panel .filepond--panel-root{
  @apply border dark:border-gray-300;
}

.filepond--root{
  @apply mb-0;
}

@media (max-width: 800px) {
    .filepond--item {
        width: calc(100%);
    }
}
</style>

