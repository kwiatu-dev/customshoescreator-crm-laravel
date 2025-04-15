<template>
  <div class="grid grid-cols-12 gap-2">
    <div class="flex col-span-12 md:col-span-4 font-medium flex-row flex-nowrap items-center justify-between border border-solid border-gray-100 dark:border-gray-500 rounded-md p-4 shadow-sm text-xl">
      <slot />
    </div>

    <Link 
      v-if="statuses['awaiting']"
      :href="statuses['awaiting']['url']"
      class="col-span-6 md:col-span-2 card text-center" 
      style="background-color: rgb(253 224 71 / .5) !important"
    >
      <font-awesome-icon :icon="['far', 'hourglass-half']" class="!text-yellow-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">Oczekujące</h2>
      <div class="font-bold text-2xl">{{ statuses['awaiting']['count'] }}</div>
    </Link>

    <Link
      v-if="statuses['in_progress']"
      :href="statuses['in_progress']['url']"
      class="col-span-6 md:col-span-2 card text-center" 
      style="background-color: rgb(251 146 60 / .5) !important"
    >
      <font-awesome-icon :icon="['fas', 'screwdriver-wrench']" class="!text-orange-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">W trakcie</h2>
      <div class="font-bold text-2xl">{{ statuses['in_progress']['count'] }}</div>
    </Link>

    <Link
      v-if="statuses['after_deadline']"
      :href="statuses['after_deadline']['url']" 
      class="col-span-6 md:col-span-2 card text-center" 
      style="background-color: rgb(248 113 113 / .5) !important;"
    >
      <font-awesome-icon :icon="['fas', 'triangle-exclamation']" class="!text-red-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">Po czasie</h2>
      <div class="font-bold text-2xl">{{ statuses['after_deadline']['count'] }}</div>
    </Link>

    <Link 
      v-if="statuses['completed']"
      :href="statuses['completed']['url']"
      class="col-span-6 md:col-span-2 card text-center " 
      style="background-color: rgb(74 222 128 / .5) !important;"
    >
      <font-awesome-icon :icon="['fas', 'flag-checkered']" class="!text-green-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">Zakończone</h2>
      <div class="font-bold text-2xl ">{{ statuses['completed']['count'] }}</div>
    </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  statuses: {
    type: Object,
    required: true,
  },
})
</script>