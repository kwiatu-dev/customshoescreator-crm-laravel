import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { visualizer } from 'rollup-plugin-visualizer'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue({
      template: {
        base: null,
        includeAbsolute: false,
      },
    }),
    visualizer({
      filename: 'bundle-report.html', 
      open: true,                     
      gzipSize: true,
      brotliSize: true,
    }),
  ],
  resolve: {
    alias: {
      ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
    },
  },
  define: {
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
  },
  build: {
    target: 'esnext',
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes('node_modules')) {
            if (id.includes('vue')) return 'vendor-vue'
            if (id.includes('axios')) return 'vendor-axios'
            if (id.includes('lodash')) return 'vendor-lodash'
            if (id.includes('ziggy')) return 'vendor-ziggy'
            if (id.includes('@fullcalendar')) return 'vendor-fullcalendar'
            if (id.includes('filepond')) return 'vendor-filepond'
            if (id.includes('chart.js')) return 'vendor-chartjs'
            if (id.includes('@fortawesome')) return 'vendor-fontawesome' 
            return 'vendor'
          }
        },
      },
    },
  },
})
