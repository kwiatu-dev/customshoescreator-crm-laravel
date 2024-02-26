<template>
  <header>
    <nav class="bg-gray-100 border-gray-200 dark:bg-gray-800 ">
      <div class="flex flex-wrap justify-between items-center mx-auto container px-4 lg:px-6 py-2.5">
        <div class="w-full lg:w-auto flex justify-between items-center lg:order-2">
          <div>
            <a v-if="!currentUser" :href="route('login')" class="text-indigo-600 dark:text-indigo-400 bg-gray-200 hover:bg-gray-50 dark:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 inline-block">Zaloguj się</a>
            <a v-if="currentUser" href="#" class="text-indigo-600 dark:text-indigo-400 bg-gray-200 hover:bg-gray-50 dark:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 inline-block">{{ currentUser.first_name }}</a>
            <Link v-if="currentUser" :href="route('logout')" class="text-red-600 dark:text-red-600 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 inline-block" as="button" method="delete">Wyloguj</Link>
          </div>
          
          <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false" @click="toggle">
            <span class="sr-only">Open main menu</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
            <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
          </button>
        </div>
        <div id="mobile-menu-2" class="justify-between items-center w-full lg:flex lg:w-auto lg:order-1" :class="{'hidden': menu}">
          <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
            <li>
              <Link :href="route('home')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Kokpit</Link>
            </li>
            <li>
              <Link :href="route('client.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Klienci</Link>
            </li>
            <li v-if="currentUser?.is_admin">
              <Link :href="route('user.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Użytkownicy</Link>
            </li>
            <li v-if="currentUser?.is_admin">
              <Link :href="route('expenses.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Wydatki</Link>
            </li>
            <li v-if="currentUser?.is_admin">
              <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Przychód</a>
            </li>
            <li v-if="currentUser?.is_admin">
              <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Inwestycje</a>
            </li>
            <li>
              <Link :href="route('projects.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Projekty</Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'


const page = usePage()
const menu = ref(true)

const toggle = () => {
  menu.value = !menu.value
}

const currentUser = computed(
  () => page.props.currentUser,
)
</script>