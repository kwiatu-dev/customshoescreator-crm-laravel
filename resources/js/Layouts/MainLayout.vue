<template>
  <Header />
  <main class="p-4 mx-auto w-full">
    <FlashMassage />
    <slot />
  </main>
  <ChatWidget />
</template>

<script setup>
import { defineAsyncComponent } from 'vue'
import Header from '@/Layouts/Header.vue'
import FlashMassage from '@/Components/UI/FlashMassage.vue'

const ChatWidget = defineAsyncComponent(() => import('@/Chat/ChatWidget.vue'))
</script>
