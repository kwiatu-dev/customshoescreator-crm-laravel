<template>
  <header class="antialiased w-full mx-auto px-4 bg-gray-100 dark:bg-gray-800">
    <nav class="py-8">
      <div class="flex flex-wrap justify-between items-center">
        <div class="flex justify-start items-center">
          <!-- <button id="toggleSidebar" aria-expanded="true" aria-controls="sidebar" class="hidden p-2 mr-3 text-gray-600 rounded cursor-pointer lg:inline hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12"> <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h14M1 6h14M1 11h7" /> </svg>
          </button>
          <button aria-expanded="true" aria-controls="sidebar" class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-[18px] h-[18px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" /></svg>
            <span class="sr-only">Toggle sidebar</span>
          </button> -->
          <a href="/" class="flex items-center mr-4">
            <img src="/storage/logo.webp" class="mr-3 h-10 rounded-full" />
            <div>
              <p class="hidden sm:block self-center text-2xl font-semibold whitespace-nowrap dark:text-white mb-0 -mt-2">panelCSC</p>
              <p class="hidden sm:block text-sm -mt-1 text-gray-500">System do zarządzania zleceniami.</p>
            </div>

          </a>
        </div>
        <div class="flex items-center lg:order-2">          
          <!-- Notifications -->
          <button 
            id="notificationsButton"
            ref="notificationsButton"
            :disabled="user === null"
            type="button" 
            data-dropdown-toggle="notification-dropdown" 
            :class="{ 'cursor-not-allowed': user === null }"
            class="p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            @click="toggleNotifications"
          >
            <span class="sr-only">View notifications</span>
            <span class="notification-count">1</span>
            <!-- Bell icon -->
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20"><path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" /></svg>
          </button>
          <!-- Dropdown menu -->
          <div 
            id="notification-dropdown"
            ref="notificationsDropdown" 
            class="overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700"
            :class="{ 'hidden': notifications }"
          >
            <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              Powiadomienia
            </div>
            <div>
              <div href="#" class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                <div class="pl-3 w-full">
                  <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">
                    New message from 
                    <span class="font-semibold text-gray-900 dark:text-white">
                      Bonnie Green
                    </span>
                    : "Hey, what's up? All set for the presentation?"
                  </div>
                  <div class="text-xs font-medium text-primary-700 dark:text-primary-400">
                    a few moments ago
                  </div>
                </div>
                <button class="hover:text-gray-500 p-2">
                  <font-awesome-icon :icon="['fas', 'trash-can']" />
                </button>
              </div>
            </div>
            <a href="#" class="block py-2 text-base font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:underline">
              <div class="inline-flex items-center ">
                <svg aria-hidden="true" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                Zobacz wszystkie
              </div>
            </a>
          </div>
          <!-- Apps -->
          <button
            ref="menuButton"
            type="button" data-dropdown-toggle="apps-dropdown" class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            style="padding: .7rem;"
            @click="toggleMenu"
          >
            <span class="sr-only">View notifications</span>
            <!-- Icon -->
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
              <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
            </svg>              
          </button>
          <!-- Dropdown menu -->
          <div 
            id="apps-dropdown"
            ref="menuDropdown" 
            class="overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:bg-gray-700 dark:divide-gray-600"
            :class="{ 'hidden': menu }"
          >
            <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              Menu
            </div>
            <div class="grid grid-cols-3 gap-4 p-4">
              <a 
                :href="route('dashboard.index')"  
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'chart-pie']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Kokpit</div>
              </a>
              <Link 
                v-if="user?.is_admin"
                :href="route('user.index')" 
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'users']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Użytkownicy</div>
              </Link>
              <Link 
                :href="route('client.index')" 
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'people-group']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Klienci</div>
              </Link>
              <Link 
                v-if="user?.is_admin"
                :href="route('investments.index')" 
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'money-bill-trend-up']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Inwestycje</div>
              </Link>
              <Link 
                :href="route('projects.index')"  
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'pen-ruler']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Projekty</div>
              </Link>
              <Link 
                :href="route('organizer.index')"  
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'calendar-days']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />                
                <div class="text-sm font-medium text-gray-900 dark:text-white">Organizer</div>
              </Link>
              <Link
                v-if="user?.is_admin" 
                :href="route('incomes.index')" 
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'circle-dollar-to-slot']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Przychód</div>
              </Link>
              <Link 
                v-if="user?.is_admin"
                :href="route('expenses.index')"
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'cart-plus']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Wydatki</div>
              </Link>
              <Link 
                v-if="user"
                :href="route('logout')" 
                as="button"
                method="delete"
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'arrow-right-from-bracket']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Wyloguj</div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { useAuthUser } from '@/Composables/useAuthUser'
import { Link, usePage } from '@inertiajs/vue3'
import { onBeforeUnmount, onMounted, ref} from 'vue'

const page = usePage()
const user = useAuthUser()
const menu = ref(true)
const menuButton = ref(null)
const menuDropdown = ref(null)
const notifications = ref(true)
const notificationsButton = ref(null)
const notificationsDropdown = ref(null)

const toggleMenu = () => {
  menu.value = !menu.value
}

const toggleNotifications = () => {
  notifications.value = !notifications.value
}

const isActive = (name) => {
  return page.props.ziggy.location.startsWith(route(name))
}

const handleMenuClickOutside = (event) => {
  if (
    menuButton.value && !menuButton.value.contains(event.target) &&
    menuDropdown.value && !menuDropdown.value.contains(event.target)
  ) {
    menu.value = true
  }
}

const handleNotificationsClickOutside = (event) => {
  if (
    notificationsButton.value && !notificationsButton.value.contains(event.target) &&
    notificationsDropdown.value && !notificationsDropdown.value.contains(event.target)
  ) {
    notifications.value = true
  }
}

onMounted(() => {
  document.addEventListener('click', handleMenuClickOutside)
  document.addEventListener('click', handleNotificationsClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleMenuClickOutside)
  document.removeEventListener('click', handleNotificationsClickOutside)
})
</script>

<style scoped>
#apps-dropdown {
  position: absolute;
  top: 65px;
  right: 0;
  margin-top: 20px;
}

#notification-dropdown {
  position: absolute;
  top: 65px;
  right: 0;
  margin-top: 20px;
}

#notificationsButton {
  position: relative;
}

.notification-count {
  position: absolute;
  top: -1px;
  right: -1px;
  color: white;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .6rem;
  font-weight: bold;

  @apply bg-red-500 dark:bg-red-600;
}
</style>