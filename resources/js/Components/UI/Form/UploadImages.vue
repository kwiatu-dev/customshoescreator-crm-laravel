<template>
  <file-pond 
    ref="pond"
    name="filepond"
    class-name="filepond"
    :label-idle="labelIdle"
    :allow-multiple="allowMultiple"
    :accepted-file-types="acceptedFileTypes"
    :max-files="maxFiles"
    max-file-size="8MB"
    style-item-panel-aspect-ratio="1"
    force-revert="true"
    label-file-waiting-for-size="Trwa pobieranie rozmiaru"
    label-max-file-size-exceeded="Plik jest za duży"
    label-max-file-size="Dozwolona wielkość pliku: {filesize}"
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
        onload: onLoad,
        onerror: onError,
      },
      load: (source, load, error, progress, abort, headers) => {
        axios.get(source, { responseType: 'blob' })
          .then(response => load(response.data))
          .catch(reason => error())
      },
      revert: onRevert,
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
    }"
    @removefile="onRemoveFile"
    @init="emit('init')"
  />
  <FormError 
    v-for="(error, image_id) in errors" 
    :key="image_id" 
    :error="error.join('\n')"
  />
</template>

<script setup>
import FormError from '@/Components/UI/Form/FormError.vue'
import vueFilePond from 'vue-filepond'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const FilePond = vueFilePond(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFileValidateSize)
const page = usePage()
const pond = ref(null)
const csrfToken = computed(() => page.props.csrfToken)

const props = defineProps({
  labelIdle: {
    type: String,
    default: '<b>Kliknij, aby przesłać</b> lub przeciągnij i upuść (max 10)',
  },
  allowMultiple: {
    type: Boolean,
    default: true,
  },
  acceptedFileTypes: {
    type: String,
    default: 'image/*',
  },
  maxFiles: {
    type: Number,
    default: 10,
  },
  images: Array,
  errors: Object,
})

const emit = defineEmits([
  'update:images', 
  'update:errors', 
  'init',
])

const onLoad = (uniqueId) => {
  emit('update:images', [...props.images, uniqueId])
  return uniqueId
}

const onRevert = async (uniqueId, load, error) => {
  const index = props.images.indexOf(uniqueId)
  const { default: axios } = await import('axios')
  try{
    await axios.delete(route('filepond.destroy', { filepond: uniqueId }))
    emit('update:images', props.images.filter((_, i) => i !== index))
  }
  catch(e){
    const image = pond.value.getFiles().find(image => image.serverId === uniqueId)
    emit('update:errors', { ...props.errors, ...{ [image.id]: [`Wystąpił błąd podczas usuwania zdjęcia: ${image.filename}`] } })
    error('')
  }
}

const onError = (response) => {
  const errors = JSON.parse(response)?.filepond
  const images = pond.value.getFiles()
  const image = images[0]

  if(errors && errors?.length){
    emit('update:errors', { ...props.errors, ...{ [image.id]: errors } })
  }
}

const onRemoveFile = (e, file) => {
  const index = props.images.indexOf(file.filename)
  emit('update:images', props.images.filter((_, i) => i !== index))

  const errors = Object.keys(props.errors).reduce((acc, key) => {
    if(key !== file.id){
      acc[key] = props.errors[key]
    }

    return acc
  }, {})

  emit('update:errors', errors)
}

const addImages = (images, options) => {
  pond.value.addFiles(images.map(image => ({
    source: window.location.origin + '/private/files/' + image,
    options,
  })))
}

const clearImages = () => {
  pond.value.removeFiles({ revert: true })
}

defineExpose({
  addImages,
  clearImages,
})
</script>

