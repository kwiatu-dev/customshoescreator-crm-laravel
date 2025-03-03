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
import { inject } from 'vue'
  
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

const disableRememberState = inject('disable_remember_state', false)
  
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

  console.log(data)

  if (!disableRememberState) {
    router.post(route('remember.state'), data) 
  } 
  else {
    router.visit(props.url)
  }
}
</script>