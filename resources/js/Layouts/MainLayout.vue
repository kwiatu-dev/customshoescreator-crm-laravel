<template>
  <Header />
  <main class="p-4 mx-auto w-full">
    <FlashMassage />
    <slot />
  </main>
  <!-- <ChatWidget v-if="user" /> -->
</template>

<script setup>
import { defineAsyncComponent } from 'vue'
import Header from '@/Layouts/Header.vue'
import FlashMassage from '@/Components/UI/FlashMassage.vue'
import { useAuthUser } from '@/Composables/useAuthUser'
const user = useAuthUser()
const ChatWidget = defineAsyncComponent(() => import('@/Chat/ChatWidget.vue'))
</script>
