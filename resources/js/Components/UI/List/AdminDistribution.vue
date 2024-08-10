<template>
  <div>
    <div>Filip {{ filip }}%</div>
    <div>Aleksandra {{ aleksandra }}%</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  object: { type: Object, required: true },
})

const filip = computed(() => JSON.parse(props.object.distribution)['1'])
const aleksandra = computed(() => JSON.parse(props.object.distribution)['2'])
</script>