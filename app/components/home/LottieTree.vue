<template>
  <div class="lottie-tree-wrapper">
    <ClientOnly>
      <div ref="lottieContainer" class="lottie-animation"></div>
      <template #fallback>
        <div class="tree-fallback">
          <div class="fallback-tree"></div>
        </div>
      </template>
    </ClientOnly>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

// House with tree animation from LottieFiles - selected by user (local file)
const ANIMATION_URL = '/animations/Home.json'

const lottieContainer = ref<HTMLDivElement | null>(null)
let animationInstance: any = null

// Load script dynamically only on client side
const loadLottieScript = (): Promise<void> => {
  return new Promise((resolve, reject) => {
    // Check if already loaded
    if ((window as any).lottie) {
      resolve()
      return
    }
    
    // Check if script is already in DOM
    const existingScript = document.querySelector('script[src*="lottie"]')
    if (existingScript) {
      existingScript.addEventListener('load', () => resolve())
      return
    }
    
    // Create and append script
    const script = document.createElement('script')
    script.src = 'https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie_light.min.js'
    script.async = true
    script.onload = () => resolve()
    script.onerror = () => reject(new Error('Failed to load lottie-web'))
    document.head.appendChild(script)
  })
}

const initAnimation = () => {
  if (!lottieContainer.value || typeof window === 'undefined') return
  
  const lottie = (window as any).lottie
  if (!lottie) return
  
  // Destroy existing animation if any
  if (animationInstance) {
    animationInstance.destroy()
  }
  
  animationInstance = lottie.loadAnimation({
    container: lottieContainer.value,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: ANIMATION_URL
  })
}

onMounted(async () => {
  try {
    await loadLottieScript()
    // Small delay to ensure DOM is ready
    requestAnimationFrame(() => {
      initAnimation()
    })
  } catch (error) {
    console.warn('Lottie animation could not be loaded:', error)
  }
})

onUnmounted(() => {
  if (animationInstance) {
    animationInstance.destroy()
    animationInstance = null
  }
})
</script>

<style scoped>
.lottie-tree-wrapper {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  width: 100%;
  height: 250px;
  overflow: hidden;
}

.lottie-animation {
  width: 400px;
  height: 320px;
  margin-bottom: -30px;
}

.tree-fallback {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  height: 100%;
}

.fallback-tree {
  width: 180px;
  height: 180px;
  border-radius: 50% 50% 0 0;
  background: linear-gradient(180deg, #4ade80 0%, #22c55e 50%, #16a34a 100%);
  box-shadow: 0 -10px 40px rgba(34, 197, 94, 0.4);
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.02); }
}
</style>
