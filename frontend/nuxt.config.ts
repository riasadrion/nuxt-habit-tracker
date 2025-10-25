// nuxt.config.ts
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  ssr: false,
  devtools: { enabled: true },
  modules: ['@pinia/nuxt'],
  css: ['bootstrap/dist/css/bootstrap.min.css', '@/assets/css/main.css'],
  app: {
    head: {
      title: 'Virtual Pet Habit Tracker',
      meta: [
        { name: 'charset', content: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        {
          name: 'description',
          content:
            'Keep your virtual pets healthy by completing daily habit loops in a friendly dashboard.'
        }
      ]
    }
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api'
    }
  },
  nitro: {
    devProxy: {
      '/api': {
        target: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api',
        changeOrigin: true
      }
    }
  },
  imports: {
    dirs: ['stores']
  }
})
