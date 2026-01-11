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
import { ref, onMounted, onUnmounted, watch } from 'vue'

// House with tree animation from LottieFiles - selected by user (local file)
const ANIMATION_URL = '/animations/Home.json'

const lottieContainer = ref<HTMLDivElement | null>(null)
const scriptLoaded = ref(false)
let animationInstance: any = null

// Load lottie-web via useHead
useHead({
  script: [
    {
      src: 'https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js',
      defer: true,
      onload: () => {
        scriptLoaded.value = true
      }
    }
  ]
})

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

// Watch for script load
watch(scriptLoaded, (loaded) => {
  if (loaded) {
    initAnimation()
  }
})

onMounted(() => {
  // Check if already loaded
  if ((window as any).lottie) {
    initAnimation()
  } else {
    // Poll for lottie library availability
    const checkLottie = setInterval(() => {
      if ((window as any).lottie) {
        clearInterval(checkLottie)
        initAnimation()
      }
    }, 100)
    
    // Clear interval after 10 seconds
    setTimeout(() => clearInterval(checkLottie), 10000)
  }
})

onUnmounted(() => {
  if (animationInstance) {
    animationInstance.destroy()
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
