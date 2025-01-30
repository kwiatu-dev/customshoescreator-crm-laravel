<template>
  <div
    class="w-96 z-50 fixed left-4 bottom-4 p-4 mb-4 text-red-800 border-2 border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800 transition-all delay-150 duration-300 ease-in-out"
    :class="{ ['translate-x-0 opacity-100']: showFailed, ['-translate-x-full opacity-0']: !showFailed }"
  >
    <div class="flex items-center">
      <font-awesome-icon :icon="['fas', 'circle-info']" />
      <h3 class="text-lg font-medium ml-2">Błąd podczas wykonywania zadania</h3>
    </div>
    <div class="mt-2 mb-4 text-sm">
      {{ failed }}
    </div>
    <div class="flex">
      <button 
        type="button"
        class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800" 
        @click="hideFlashMessage"
      >
        <font-awesome-icon :icon="['fas', 'eye-slash']" />
        <span class="ml-2">Ukryj</span>
      </button>
    </div>
  </div>
  <div
    class="w-96 z-50 fixed left-4 bottom-4 p-4 mb-4 text-green-800 border-2 border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 transition-all delay-150 duration-300 ease-in-out"
    :class="{ ['translate-x-0 opacity-100']: showSuccess, ['-translate-x-full opacity-0']: !showSuccess }"
  >
    <div class="flex items-center">
      <font-awesome-icon :icon="['fas', 'circle-info']" />
      <h3 class="text-lg font-medium ml-2">Zadanie zostało wykonane pomyślnie</h3>
    </div>
    <div class="mt-2 mb-4 text-sm">
      {{ success }}
    </div>
    <div class="flex">
      <button 
        type="button"
        class="text-green-800 bg-transparent border border-green-800 hover:bg-green-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-green-600 dark:border-green-600 dark:text-green-400 dark:hover:text-white dark:focus:ring-green-800" 
        @click="hideFlashMessage"
      >
        <font-awesome-icon :icon="['fas', 'eye-slash']" />
        <span class="ml-2">Ukryj</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watchEffect } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const success = computed(
  () => page.props.flash.success,
)

const failed = computed(
  () => page.props.flash.failed,
)

const showSuccess = ref(null)
const showFailed = ref(null)

const hideFlashMessage = () => {
  showSuccess.value = false
  showFailed.value = false

  setTimeout(() => {
    page.props.flash.success = null
    page.props.flash.failed = null
  }, 300)
}

watchEffect(() => {
  if (success.value) {
    showSuccess.value = true
    setTimeout(() => hideFlashMessage(), 5000) 
  }
  if (failed.value) {
    showFailed.value = true
    setTimeout(() => hideFlashMessage(), 5000)
  }
})
</script>
