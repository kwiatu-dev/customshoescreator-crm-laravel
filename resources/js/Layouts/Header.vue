<template>
  <header>
    <nav class="bg-gray-100 border-gray-200 dark:bg-gray-800 ">
      <div class="flex flex-wrap justify-between items-center mx-auto p-4">
        <div class="w-full lg:w-auto flex justify-between items-center lg:order-2">
          <UserMenu v-if="user" class="order-2" />
          <button type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:bg-gray-700 dark:focus:ring-gray-600" @click="toggle">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
          </button>
        </div>
        <div class="justify-between items-center w-full lg:flex lg:w-auto" :class="{'hidden': menu}">
          <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
            <li>
              <Link
                :href="route('home')" 
                class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Kokpit
              </Link>
            </li>
            <li>
              <Link 
                :class="{'active': isActive('client.index')}"
                :href="route('client.index')" 
                class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Klienci
              </Link>
            </li>
            <li v-if="user?.is_admin">
              <Link 
                :class="{'active': isActive('user.index')}"
                :href="route('user.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Użytkownicy
              </Link>
            </li>
            <li v-if="user?.is_admin">
              <Link 
                :class="{'active': isActive('expenses.index')}"
                :href="route('expenses.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Wydatki
              </Link>
            </li>
            <li v-if="user?.is_admin">
              <Link 
                :class="{'active': isActive('incomes.index')}"
                :href="route('incomes.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Przychód
              </Link>
            </li>
            <li v-if="user?.is_admin">
              <Link 
                :class="{'active': isActive('investments.index')}"
                :href="route('investments.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Inwestycje
              </Link>
            </li>
            <li>
              <Link 
                :class="{'active': isActive('projects.index')}"
                :href="route('projects.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Projekty
              </Link>
            </li>
            <li>
              <Link 
                :class="{'active': isActive('organizer.index')}"
                :href="route('organizer.index')" class="block py-2 pr-4 pl-3 text-gray-700 border-gray-300 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
              >
                Organizator pracy
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import UserMenu from '@/Components/UI/User/UserMenu.vue'
import { useAuthUser } from '@/Composables/useAuthUser'
import { Link, usePage } from '@inertiajs/vue3'
import { ref} from 'vue'

const user = useAuthUser()
const menu = ref(true)
const page = usePage()

const toggle = () => {
  menu.value = !menu.value
}

const isActive = (name) => {
  return page.props.ziggy.location.startsWith(route(name))
}
</script>

<style scoped>
a.active {
  @apply text-gray-50;
}
</style>