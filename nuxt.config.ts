// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxt/image'],
  css: ['~/assets/styles/main.css'],
  runtimeConfig: {
    public: {
      apiUrl: process.env.NUXT_PUBLIC_API_URL || 'http://localhost:8000/api',
      googleMapsApiKey: process.env.NUXT_PUBLIC_GOOGLE_MAPS_API_KEY || ''
    }
  },
  routeRules: {
    '/admin/**': { ssr: false },
    '/login': { ssr: false },
    '/register': { ssr: false },
    '/forgot-password': { ssr: false },
    '/reset-password': { ssr: false }
  },
  nitro: {
    devProxy: {
      '/api': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
      }
    }
  }
})
