<template>
  <div class="view-show">
    <h1 class="title mb-8">Lista powiadomień</h1>
    <div class="flex flex-col gap-4">
      <div 
        v-for="(notification, index) in notifications.data" 
        :key="notification.id" 
        class="overflow-hidden bg-gray-200 dark:bg-gray-800 hover:shadow-md rounded-sm shadow-sm p-4 flex flex-row flex-nowrap justify-between items-center relative cursor-pointer"
        :class="{
          'opacity-30': notification.read_at !== null,
        }"
        @click="toggleActionVisibility(index)"
      >
        <div class="w-full">
          <div class="text-md text-gray-800 dark:text-gray-300">
            <NotificationText :notification="notification" />
          </div>
          <div class="text-xs font-bold text-gray-500 dark:text-gray-600 mt-2">{{ useNotificationTimeAgo(notification.created_at).timeAgo }}</div>
        </div>
        <div 
          class="absolute top-0 right-0 h-full flex items-center transition-transform duration-300"
          :class="visibleActionIndexes.includes(index) ? 'translate-x-0' : 'translate-x-full'"
        >
          <Link
            v-if="notification.read_at === null" 
            :href="route('notifications.seen', { notification: notification.id })"
            as="button" 
            method="PUT" 
            class="text-2xl text-gray-50 rounded-r-sm bg-green-400 dark:bg-green-600 w-28 h-full flex flex-row justify-center items-center shadow-md hover:bg-green-300 hover:dark:bg-green-700"
          >
            <font-awesome-icon :icon="['fas', 'envelope-open']" />
          </Link>
        </div>
      </div>
      <div v-if="notifications.data.length === 0" class="">
        Brak powiadomień
      </div>
    </div>
    <div 
      class="flex flex-row flex-nowrap justify-center items-center mt-4"
    >
      <Pagination 
        v-if="notifications.total > notifications.per_page"
        :links="notifications.links"
      />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineAsyncComponent, ref } from 'vue'
import { useNotificationTimeAgo } from '@/Composables/useNotificationTimeAgo'

const Pagination = defineAsyncComponent(() => import('@/Components/UI/List/Pagination.vue'))
const NotificationText = defineAsyncComponent(() => import('@/Pages/Notification/Index/Components/NotificationText.vue'))
defineProps({
  notifications: {
    type: Object,
    required: true,
  },
})

const visibleActionIndexes = ref([])

const toggleActionVisibility = (index) => {
  if (visibleActionIndexes.value.includes(index)) {
    visibleActionIndexes.value = visibleActionIndexes.value.filter(i => i !== index)
  } else {
    visibleActionIndexes.value.push(index)
  }
}
</script>

<style scoped>
.transition-transform {
  transition: transform 0.3s ease-in-out;
}
</style>