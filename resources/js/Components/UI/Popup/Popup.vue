<template>
  <div class="fixed w-full h-full bg-gray-950 top-0 left-0 opacity-90 z-20" :class="[show ? 'block' : 'hidden']" @click="close" />
  <div class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-4/5 h-4/5 z-20 bg-gray-800 overflow-y-auto rounded-lg" :class="[show ? 'block' : 'hidden']">
    <div class="w-full flex flex-col">
      <div class="w-full bg-gray-700 p-4 text-sm">
        <div class="w-full flex flex-row justify-between items-center">
          <span>Okno dialogowe</span>
          <span class="text-xl cursor-pointer p-2" @click="close">x</span>
        </div>
      </div>
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: Boolean,
})

const emit = defineEmits(['update:show', 'close'])

const close = () => {
  emit('update:show', false)
  emit('close')
}
</script>