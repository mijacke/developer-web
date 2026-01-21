let loadPromise: Promise<void> | null = null

function loadScript(src: string): Promise<void> {
  if (typeof document === 'undefined') {
    return Promise.resolve()
  }

  return new Promise((resolve, reject) => {
    const existing = document.querySelector(`script[data-splide="1"]`) as HTMLScriptElement | null
    if (existing) {
      existing.addEventListener('load', () => resolve(), { once: true })
      existing.addEventListener('error', () => reject(new Error('Splide script failed to load')), { once: true })
      if ((existing as any).dataset?.loaded === '1') {
        resolve()
      }
      return
    }

    const script = document.createElement('script')
    script.src = src
    script.async = true
    script.dataset.splide = '1'
    script.addEventListener('load', () => {
      script.dataset.loaded = '1'
      resolve()
    })
    script.addEventListener('error', () => reject(new Error('Splide script failed to load')))
    document.head.appendChild(script)
  })
}

export const useSplide = () => {
  const isReady = useState('splide-ready', () => false)

  const ensureLoaded = async () => {
    if (process.server) return

    if ((window as any).Splide) {
      isReady.value = true
      return
    }

    if (!loadPromise) {
      loadPromise = loadScript('/vendor/splide/splide.min.js')
    }

    await loadPromise
    isReady.value = true
  }

  return {
    isReady,
    ensureLoaded,
  }
}

