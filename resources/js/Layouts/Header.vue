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
          <Link :href="route('dashboard.index')" class="flex items-center mr-4">
            <img :src="logo" class="mr-3 h-10 rounded-full" />
            <div>
              <p class="hidden sm:block self-center text-2xl font-semibold whitespace-nowrap dark:text-white mb-0 -mt-2">panelCSC</p>
              <p class="hidden sm:block text-sm -mt-1 text-gray-500">System do zarządzania zleceniami.</p>
            </div>
          </Link>
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
            <span v-if="unreadNotificationsCount" class="notification-count">{{ unreadNotificationsCount }}</span>
            <!-- Bell icon -->
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20"><path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" /></svg>
          </button>
          <!-- Dropdown menu -->
          <div 
            id="notification-dropdown"
            ref="notificationsDropdown" 
            class="overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700"
            :class="{ 'hidden': notifications }"
            style="min-width: 300px; max-height: 600px; overflow-y: auto; right: 10px;"
          >
            <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              Powiadomienia
            </div>
            <div>
              <div 
                v-for="(notification, index) in unreadNotificationsList" 
                :key="notification.id" 
                class="overflow-hidden cursor-pointer flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600 relative"
                @click="toggleNotificationActionVisibility(index)"
              >
                <div class="pl-3 w-full">
                  <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">
                    <NotificationText :notification="notification" />
                  </div>
                  <div class="text-xs font-medium text-primary-700 dark:text-primary-400">
                    {{ useNotificationTimeAgo(notification.created_at).timeAgo }}
                  </div>
                </div>
                <Link 
                  :class="visibleNotificationActionIndexes.includes(index) ? 'translate-x-0' : 'translate-x-full'"
                  class="text-xl w-24 h-full bg-green-400 hover:bg-green-300 dark:bg-green-700 hover:dark:bg-green-800 text-gray-50 absolute -mt-3 right-0 transition-transform duration-300" 
                  :href="route('notifications.seen', { notification: notification.id })" 
                  as="button" 
                  method="PUT" 
                  preserve-scroll
                >
                  <font-awesome-icon :icon="['fas', 'envelope-open']" />
                </Link>
              </div>
              <div v-if="unreadNotificationsList.length === 0" class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                Brak powiadomień
              </div>
            </div>
            <Link :href="route('notifications.index')" class="block py-2 text-base font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 hover:dark:bg-gray-600 dark:bg-gray-700 dark:text-white dark:hover:underline">
              <div class="inline-flex items-center ">
                Zobacz wszystkie
              </div>
            </Link>
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
            style="right: 10px;"
          >
            <div class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              Menu
            </div>
            <div class="grid grid-cols-3 gap-4 p-4">
              <Link 
                :href="route('dashboard.index')"  
                class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group"
              >
                <font-awesome-icon :icon="['fas', 'chart-pie']" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400" />
                <div class="text-sm font-medium text-gray-900 dark:text-white">Kokpit</div>
              </Link>
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
import { computed, onBeforeUnmount, onMounted, ref} from 'vue'
import NotificationText from '@/Pages/Notification/Index/Components/NotificationText.vue'
import { useNotificationTimeAgo } from '@/Composables/useNotificationTimeAgo'
import logo from '@/../../public/images/logo.webp'

const page = usePage()
const user = useAuthUser()
const menu = ref(true)
const menuButton = ref(null)
const menuDropdown = ref(null)
const notifications = ref(true)
const notificationsButton = ref(null)
const notificationsDropdown = ref(null)
const unreadNotificationsCount = computed(() => user.value?.unread_notifications_count ?? 0)
const unreadNotificationsList = computed(() => user.value?.unread_notifications ?? [])
const visibleNotificationActionIndexes = ref([])

const toggleMenu = () => {
  menu.value = !menu.value
}

const toggleNotifications = () => {
  notifications.value = !notifications.value
}

const toggleNotificationActionVisibility = (index) => {
  if (visibleNotificationActionIndexes.value.includes(index)) {
    visibleNotificationActionIndexes.value = visibleNotificationActionIndexes.value.filter(i => i !== index)
  } else {
    visibleNotificationActionIndexes.value.push(index)
  }
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

.transition-transform {
  transition: transform 0.3s ease-in-out;
}
</style>