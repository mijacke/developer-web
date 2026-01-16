<template>
  <div class="dm-admin-wrap">
    <div id="dm-root" data-dm-app="developer-map" ref="dmRoot"></div>
  </div>
</template>

<script setup lang="ts">
/**
 * Developer Map Admin Page
 * Integrates original dm.js and dm.css from lib/developer-map-v2
 */

const config = useRuntimeConfig()
const dmRoot = ref<HTMLElement | null>(null)

// Runtime config for dm.js - compatible with original storage-client
const dmRuntimeConfig = {
  restBase: `${config.public.apiUrl}/developer-map/`,
  restNonce: '', // Not needed for Laravel
  ver: Date.now(),
}

onMounted(async () => {
  // Inject runtime config before loading dm.js
  if (typeof window !== 'undefined') {
    (window as any).dmRuntimeConfig = dmRuntimeConfig
    
    // Load bootstrap data
    try {
      const response = await fetch(`${config.public.apiUrl}/developer-map/bootstrap`)
      const bootstrapData = await response.json()
      ;(window as any).dmRegionBootstrap = bootstrapData
    } catch (e) {
      console.warn('[DM] Failed to load bootstrap data', e)
      ;(window as any).dmRegionBootstrap = {}
    }
  }

  // Dynamically load dm.js
  await loadDeveloperMapScript()
})

async function loadDeveloperMapScript() {
  // dm.js will find #dm-root[data-dm-app="developer-map"] and initialize itself
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
/* Ensure proper sizing for dm-root */
.dm-admin-wrap {
  min-height: 100vh;
  background: #f8fafc;
}

#dm-root {
  min-height: 100vh;
}

/* Override any conflicting styles from main app */
#dm-root.dm-root {
  font-family: 'Inter', 'Segoe UI', sans-serif;
}
</style>
