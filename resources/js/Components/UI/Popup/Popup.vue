<template>
  <teleport to="body">
    <div v-if="show" class="fixed w-full h-full bg-gray-700 dark:bg-gray-950 top-0 left-0 opacity-90 z-50" :class="[show ? 'block' : 'hidden']" @click="close" />
    <div v-if="show" class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full md:w-4/5 md:h-4/5 z-50 bg-gray-100 dark:bg-gray-800 overflow-y-auto md:rounded-lg" :class="[show ? 'block' : 'hidden']">
      <div class="w-full flex flex-col">
        <div class="w-full bg-gray-200 dark:bg-gray-700 p-4 text-sm">
          <div class="w-full flex flex-row justify-end items-center">
            <!-- <span>Okno dialogowe</span> -->
            <span class="text-xl cursor-pointer p-2 dark:text-gray-400 text-gray-700 hover:text-gray-900 dark:hover:text-gray-300" @click="close">
              <font-awesome-icon :icon="['far', 'circle-xmark']" />
            </span>
          </div>
        </div>
        <slot />
      </div>
    </div>
  </teleport>
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