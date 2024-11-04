import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import { ZiggyVue } from 'ziggy'
import '../css/app.css'
import { fullscreenImagePlugin } from 'vue-3-fullscreen-image-directive-plugin'
import 'vue-3-fullscreen-image-directive-plugin/style.css'

createInertiaApp({
  progress: { 
    color: '#29d',
    delay: 0,
    showSpinner: true,
  },
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || MainLayout

    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(fullscreenImagePlugin)
      .mount(el)
  },
})