<template>
  <Header />
  <main class="p-4 mx-auto w-full">
    <FlashMassage />
    <slot />
  </main>
</template>

<script setup>
import Header from '@/Layouts/Header.vue'
import FlashMassage from '@/Components/UI/FlashMassage.vue'
</script>
