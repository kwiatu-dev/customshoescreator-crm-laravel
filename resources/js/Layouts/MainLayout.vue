<template>
  <Header :current-user="currentUser" />
  <main class="container p-4 mx-auto w-full">
    <div v-if="flashSuccess" class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
      {{ flashSuccess }}
    </div>
    <div v-if="flashFailed" class="mb-4 border rounded-md shadow-sm border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900 p-2">
      {{ flashFailed }}
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
</script>