<template>
  <button  
    class="btn-action"
    @click="clicked"
  >
    {{ label }}
  </button>
</template>
  
<script setup>
import { router } from '@inertiajs/vue3'
  
const props = defineProps({
  url: {
    required: true,
    type: String,
  },
  label: {
    required: true,
    type: String,
  },
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
  let scrollX, scrollY

  if (element) {
    scrollX = element.scrollLeft
    scrollY = window.scrollY
  }


  const data = {
    url: props.url,
    params: {
      scrollX,
      scrollY,
      ...queries,
    },
  }
  
  //router.post(route('remember.state'), data) 
  //todo: naprawić bład po przejściu na stronę show page przycisk remeber state powinien nie zapamiętywać
}
</script>