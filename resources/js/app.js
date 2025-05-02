import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import { ZiggyVue } from 'ziggy'
import '../css/app.css'
import { fullscreenImagePlugin } from 'vue-3-fullscreen-image-directive-plugin'
import 'vue-3-fullscreen-image-directive-plugin/style.css'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
  faCircleInfo,
  faEyeSlash,
  faFilter,
  faFilterCircleXmark,
  faAnglesLeft,
  faAnglesRight,
  faUser,
  faBell,
  faArrowRightFromBracket,
  faEnvelopeOpen,
  faChartPie,
  faUsers,
  faPeopleGroup,
  faMoneyBillTrendUp,
  faPenRuler,
  faCalendarDays,
  faCircleDollarToSlot,
  faCartPlus,
  faPlus,
  faCaretUp,
  faCaretDown,
  faScrewdriverWrench,
  faTriangleExclamation,
  faFlagCheckered,
  faTrophy,
  fa2,
  fa3,
  faMoneyBillTransfer,
  faSackDollar,
  faWallet,
} from '@fortawesome/free-solid-svg-icons'

import {
  faCircleXmark,
  faAddressCard,
  faHourglassHalf,
} from '@fortawesome/free-regular-svg-icons'

library.add(
  faCircleInfo,
  faEyeSlash,
  faFilter,
  faFilterCircleXmark,
  faAnglesLeft,
  faAnglesRight,
  faUser,
  faBell,
  faArrowRightFromBracket,
  faEnvelopeOpen,
  faChartPie,
  faUsers,
  faPeopleGroup,
  faMoneyBillTrendUp,
  faPenRuler,
  faCalendarDays,
  faCircleDollarToSlot,
  faCartPlus,
  faPlus,
  faCaretUp,
  faCaretDown,
  faScrewdriverWrench,
  faTriangleExclamation,
  faFlagCheckered,
  faTrophy,
  fa2,
  fa3,
  faCircleXmark,
  faAddressCard,
  faHourglassHalf,
  faMoneyBillTransfer,
  faSackDollar,
  faWallet,
)

const pages = import.meta.glob('./Pages/**/*.vue')

createInertiaApp({
  progress: { 
    color: '#818cf8',
    delay: 0,
    showSpinner: true,
  },
  resolve: async name => {
    const page = await pages[`./Pages/${name}.vue`]()
    page.default.layout = page.default.layout || MainLayout
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(fullscreenImagePlugin)
      .component('font-awesome-icon', FontAwesomeIcon)
      .mount(el)
  },
})


window.addEventListener('popstate', () => {
  setTimeout(() => {
    console.log('reload page after popstate')
    router.reload()
  }, 0)
})