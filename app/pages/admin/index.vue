<template>
  <div class="dm-admin-wrap" :class="{ 'show-left': showLeft, 'show-right': showRight }">
    
    <!-- Mobile Editor Toolbar -->
    <div class="mobile-editor-toolbar">
      <button 
        class="editor-toggle-btn" 
        :class="{ active: showLeft }" 
        @click="toggleLeft"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
        Zóny
      </button>
      
      <span class="toolbar-divider"></span>
      
      <button 
        class="editor-toggle-btn" 
        :class="{ active: showRight }" 
        @click="toggleRight"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        Detail
      </button>
    </div>

    <!-- Backdrop for drawers -->
    <div v-if="showLeft || showRight" class="drawer-backdrop" @click="closeDrawers"></div>

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

// Mobile Drawer State
const showLeft = ref(false)
const showRight = ref(false)

const toggleLeft = () => {
  showLeft.value = !showLeft.value
  if (showLeft.value) showRight.value = false
}

const toggleRight = () => {
  showRight.value = !showRight.value
  if (showRight.value) showLeft.value = false
}

const closeDrawers = () => {
  showLeft.value = false
  showRight.value = false
}

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
  position: relative;
}

#dm-root {
  min-height: 100%;
}

/* Force override DM font families if needed */
#dm-root.dm-root {
  font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* Override sticky header from plugin to unstick it */
#dm-root.dm-root .dm-topbar {
  position: relative !important;
  top: auto !important;
}

/* -------------------------------------------------------------------------- */
/* MOBILE DRAWER SYSTEM (<1024px)                                             */
/* -------------------------------------------------------------------------- */

.mobile-editor-toolbar {
  display: none;
}

.drawer-backdrop {
  display: none;
}

@media (max-width: 1023px) {
  /* Toolbar Styles */
  .mobile-editor-toolbar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 10px;
    background: white;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 900;
  }

  .editor-toggle-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid #cbd5e1;
    background: transparent;
    color: #475569;
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
  }

  .editor-toggle-btn.active {
    background: #cb9b51;
    color: white;
    border-color: #cb9b51;
  }

  .toolbar-divider {
    width: 1px;
    height: 20px;
    background: #e2e8f0;
  }

  /* Backdrop */
  .drawer-backdrop {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    z-index: 1000;
    animation: fadeIn 0.2s;
  }

  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

  /* FIX: Ensure Editor Grid allows positioning */
  #dm-root.dm-root .dm-editor__body {
     display: block !important; /* Stack context, but we use drawers */
     position: relative !important;
     height: auto !important;
     overflow: hidden !important; /* To contain map */
  }

  /* Force Center Panel (Map) to be always visible and full height */
  #dm-root.dm-root .dm-editor__panel--center {
    display: flex !important;
    width: 100% !important;
    height: calc(100vh - 140px) !important; /* Subtract header/toolbar height approx */
    grid-row: auto !important;
    grid-column: auto !important;
  }
  
  /* HIDE Panels by default */
  #dm-root.dm-root .dm-editor__panel--left,
  #dm-root.dm-root .dm-editor__panel--right {
    display: none !important;
    position: fixed !important;
    top: 0;
    bottom: 0;
    width: 85% !important;
    max-width: 320px !important;
    background: white !important;
    z-index: 1100 !important; /* Above backdrop */
    box-shadow: 0 0 24px rgba(0,0,0,0.2) !important;
    overflow-y: auto !important;
    flex-direction: column !important;
    border: none !important;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }

  /* Left Panel Drawer (Zones) */
  #dm-root.dm-root .dm-editor__panel--left {
    left: 0;
    transform: translateX(-100%);
  }

  /* Right Panel Drawer (Detail) */
  #dm-root.dm-root .dm-editor__panel--right {
    right: 0;
    transform: translateX(100%);
  }

  /* Show States */
  .dm-admin-wrap.show-left #dm-root.dm-root .dm-editor__panel--left {
    display: flex !important;
    transform: translateX(0);
  }

  .dm-admin-wrap.show-right #dm-root.dm-root .dm-editor__panel--right {
    display: flex !important;
    transform: translateX(0);
  }
  
  /* Adjust internal padding of panels for mobile */
  #dm-root.dm-root .dm-editor__panel-content {
    padding: 16px !important;
  }
}
</style>
