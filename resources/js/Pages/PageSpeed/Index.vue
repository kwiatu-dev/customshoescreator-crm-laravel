<template>
  <div v-if="data">
    {{ data }}
  </div>
  <div v-else>
    <form @submit.prevent="audit">
      <div>
        <label>Adres URL</label>
        <input v-model="form.url" type="text" />
        <div v-if="form.errors.url">
          {{ form.errors.url }}
        </div>
      </div>
      <button type="submit">Zapisz</button>
      <div v-if="loading" class="loading-spinner" />
    </form>
  </div>
</template>

<script setup>
import {ref} from 'vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
  data: Object,
})

const form = useForm({
  url: null,
})

const loading = ref(false)

const audit = () => {
  loading.value = true
  form.post('/audit', {
    onSuccess: () => loading.value = false,
  })
}
</script>

<style scoped>
.loading-spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid #007bff;
  width: 30px;
  height: 30px;
  animation: spin 2s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>