<template>
  <div class="dm-admin-wrap">
    <div id="dm-root" data-dm-app="developer-map" ref="dmRoot"></div>
  </div>
</template>

<script setup lang="ts">
/**
 * Developer Map Admin Page
 * Integrates original dm.js with authentication
 */

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

const config = useRuntimeConfig()
const { ensureCsrfCookie, csrfHeaders } = useAuth()
const dmRoot = ref<HTMLElement | null>(null)

// Intercept window.fetch to forcefully inject credentials for all requests to developer-map API
// This ensures dm.js requests are authenticated via cookies
let originalFetch: any = null

onMounted(async () => {
  if (typeof window !== 'undefined') {
    await ensureCsrfCookie()

    // 1. Setup fetch interceptor
    originalFetch = window.fetch
    window.fetch = async (input: RequestInfo | URL, init?: RequestInit) => {
      let url = ''
      const requestMethod =
        (init?.method ||
          (input instanceof Request ? input.method : 'GET') ||
          'GET').toString().toUpperCase()
      if (typeof input === 'string') {
        url = input
      } else if (input instanceof URL) {
        url = input.toString()
      } else if (input instanceof Request) {
        url = input.url
      }

      // Only intercept requests to our API
      if (url.includes('/developer-map') || url.includes('/api/developer-map')) {
        init = init || {}
        // Ensure cookies are sent
        init.credentials = 'include'
        
        init.headers = init.headers || {}
        // Handle both Headers object and plain object
        if (init.headers instanceof Headers) {
          init.headers.set('Accept', 'application/json')
          if (requestMethod !== 'GET') {
            const csrf = csrfHeaders()
            for (const [k, v] of Object.entries(csrf)) init.headers.set(k, v as string)
          }
        } else {
          // @ts-ignore
          init.headers['Accept'] = 'application/json'
          if (requestMethod !== 'GET') {
            // @ts-ignore
            Object.assign(init.headers, csrfHeaders())
          }
        }
      }
      
      return originalFetch(input, init)
    }

    // 2. Setup runtime config for dm.js
    const dmRuntimeConfig = {
      restBase: `${config.public.apiUrl}/developer-map/`,
      restNonce: '',
      ver: Date.now(),
      // Also provide headers here in case dm.js supports it (future proofing)
      headers: {
        'Accept': 'application/json'
      }
    }
    
    ;(window as any).dmRuntimeConfig = dmRuntimeConfig
    
    // 3. Load bootstrap data securely
    try {
      const response = await fetch(`${config.public.apiUrl}/developer-map/bootstrap`, {
        credentials: 'include',
        headers: {
          'Accept': 'application/json'
        }
      })
      if (response.ok) {
        const bootstrapData = await response.json()
        ;(window as any).dmRegionBootstrap = bootstrapData
      } else {
        console.error('[DM] Bootstrap failed', response.status)
        ;(window as any).dmRegionBootstrap = {}
      }
    } catch (e) {
      console.warn('[DM] Failed to load bootstrap data', e)
      ;(window as any).dmRegionBootstrap = {}
    }

    // 4. Load dm.js
    await loadDeveloperMapScript()
  }
})

onUnmounted(() => {
  // Restore original fetch when leaving the page
  if (originalFetch && typeof window !== 'undefined') {
    window.fetch = originalFetch
  }
})

async function loadDeveloperMapScript() {
  const script = document.createElement('script')
  script.type = 'module'
  script.src = '/lib/developer-map-v2/assets/js/dm.js?ver=' + Date.now()
  document.body.appendChild(script)
}

// Load CSS in head
useHead({
  link: [
    {
      rel: 'stylesheet',
      href: '/lib/developer-map-v2/assets/css/dm.css'
    },
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Playfair+Display:wght@400;500;600;700&family=Fira+Code:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Courier+Prime:wght@400;700&display=swap'
    }
  ]
})
</script>

<style>
/* Adjust dm-root to fit within admin layout */
.dm-admin-wrap {
  height: 100%;
  width: 100%;
}

#dm-root {
  min-height: 100%;
}

/* Force override DM font families if needed */
#dm-root.dm-root {
  font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* Override sticky header from plugin */
#dm-root.dm-root .dm-topbar {
  position: relative !important;
  top: auto !important;
}
</style>
