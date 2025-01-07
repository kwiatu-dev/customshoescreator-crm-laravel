<template>
  <Header :current-user="currentUser" />
  <main class="p-4 mx-auto w-full">
    <div v-if="flashSuccess" class="flash flash-success">
      <div>{{ flashSuccess }}</div>
      <span class="cursor-pointer p-2 text-xl" @click="hideFlashMessage">✖</span>
    </div>
    <div v-if="flashFailed" class="flash flash-failed">
      <div>{{ flashFailed }}</div>
      <div class="cursor-pointer p-2 text-xl" @click="hideFlashMessage">✖</div>
    </div>
    <slot />
  </main>
</template>

<script setup>
import {computed} from 'vue'
import Header from '@/Layouts/Header.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const flashSuccess = computed(
  () => page.props.flash.success,
)

const flashFailed = computed(
  () => page.props.flash.failed,
)

const currentUser = computed(
  () => page.props.currentUser,
)

const hideFlashMessage = () => {
  page.props.flash.success = null
  page.props.flash.failed = null
}
</script>

<style scoped>
.flash {
  @apply border rounded-md shadow-sm  p-2 px-4 flex flex-row flex-nowrap justify-between items-center;

  position: fixed;
  z-index: 10;
  left: 50px;
  bottom: 50px;
}

.flash-success {
  @apply border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900;
}

.flash-failed {
  @apply border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900;
}
</style>