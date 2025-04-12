<template>
  <div class="flex items-center space-x-3 md:space-x-0 relative">
    <button 
      type="button" 
      class="flex text-sm bg-gray-400 dark:bg-gray-800 rounded-full md:me-0 z-50" 
      :class="{['ring-2 ring-gray-200 dark:ring-gray-600 hover:ring-gray-300 dark:hover:ring-gray-500']: true}" 
      @click="toggle"
    >
      <div class="w-9 h-9 rounded-full bg-gray-300 dark:bg-gray-900 relative hover:bg-gray-400 hover:shadow-md dark:hover:bg-gray-950">
        <div 
          class="absolute w-4 h-4 bg-red-400 dark:bg-red-500 text-xs rounded-full font-medium text-gray-200" 
          style="top: -5px; left: -2px; text-shadow: 1px 1px 2px black;"
        >
          1
        </div>
        <font-awesome-icon :icon="['fas', 'user']" class="text-xl absolute left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2" />
      </div>
    </button>
    <div 
      class="z-40 my-4 text-base list-none bg-gray-100 divide-y divide-gray-200 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600 border-2 border-solid border-gray-200 dark:border-gray-600 absolute" 
      style="right: calc(100% - 7px); top: calc(50% - 7px);" 
      :class="{'hidden': menu}"
    >
      <div class="px-4 py-3">
        <span class="block text-sm text-gray-900 dark:text-white">{{ user.first_name }} {{ user.last_name }}</span>
        <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ user.email }}</span>
      </div>
      <ul>
        <li>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white w-full text-left" as="button" method="delete">
            <font-awesome-icon :icon="['far', 'address-card']" />
            <span class="ml-2">Profil</span>
          </a>
        </li>
        <li>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white w-full text-left" as="button" method="delete">
            <font-awesome-icon :icon="['fas', 'bell']" />
            <span class="ml-2">Wiadomości</span>
          </a>
        </li>
        <li>
          <Link :href="route('logout')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white w-full text-left" as="button" method="delete">
            <font-awesome-icon :icon="['fas', 'arrow-right-from-bracket']" /> 
            <span class="ml-2">Wyloguj</span>
          </Link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { useAuthUser } from '@/Composables/useAuthUser'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const user = useAuthUser()
const menu = ref(true)

const toggle = () => {
  menu.value = !menu.value
}
</script>