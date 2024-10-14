<template>
  <button 
    v-if="canEdit" 
    class="btn-action"
    @click="clicked"
  >
    Edytuj
  </button>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  object: Object,
})

const clicked = () => {
  const params = new URLSearchParams(window.location.search)
  
  params.delete('scrollX')
  params.delete('scrollY')
  
  const queries = {}
  for (const [key, value] of params.entries()) {
    queries[key] = value
  }
  
  const element = document.querySelector('.table-element')
  const scrollX = element.scrollLeft
  const scrollY = window.scrollY

  router.get(route('projects.edit', { 
    project: props.object.id,
    scrollX,
    scrollY,
    ...queries,
  })) 
}

const canEdit = computed(() => !props.object.deleted_at && props.object.editable === 1)
</script>