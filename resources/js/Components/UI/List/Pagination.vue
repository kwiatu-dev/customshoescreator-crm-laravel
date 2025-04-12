<template>
  <div v-if="isLargeScreen" class="flex gap-1">
    <Link 
      v-for="(link, index) in links" 
      :key="index" class="py-2 px-4 rounded-md" 
      :href="link.url ? link.url : '#'" 
      :class="{'btn-primary': link.active, 'hover:text-gray-950 dark:hover:text-gray-400': true}"
      v-html="link.label"
    />
  </div>
  <div v-else class="flex flex-row flex-nowrap justify-center w-full mb-2 text-gray-200">
    <Link :href="prev.url ?? '#'" as="button" class="w-8 h-8 dark:bg-indigo-600 bg-indigo-400 rounded-l-md">
      <font-awesome-icon :icon="['fas', 'angles-left']" />
    </Link>
    <div class="dark:bg-gray-500 bg-gray-400 font-medium px-4 pt-1">{{ page.label }}</div>
    <Link :href="next.url ?? '#'" as="button" class="w-8 h-8 dark:bg-indigo-600 bg-indigo-400 rounded-r-md">
      <font-awesome-icon :icon="['fas', 'angles-right']" />
    </Link>
  </div>
</template>

<script setup>
import { useMediaQuery } from '@vueuse/core'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  links: Array,
})

const isLargeScreen = useMediaQuery('(min-width: 768px)')
const prev = computed(() => props.links[0])
const next = computed(() => props.links[props.links.length - 1])
const page = computed(() => props.links.filter(link => link.active)[0])
</script>