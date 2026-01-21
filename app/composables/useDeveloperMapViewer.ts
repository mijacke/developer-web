let loadPromise: Promise<void> | null = null

function loadScript(src: string): Promise<void> {
    if (typeof document === 'undefined') {
        return Promise.resolve()
    }

    return new Promise((resolve, reject) => {
        const existing = document.querySelector(`script[data-dm-map-viewer="1"]`) as HTMLScriptElement | null
        if (existing) {
            existing.addEventListener('load', () => resolve(), { once: true })
            existing.addEventListener('error', () => reject(new Error('Developer map viewer script failed to load')), { once: true })
            if ((existing as any).dataset?.loaded === '1') {
                resolve()
            }
            return
        }

        const script = document.createElement('script')
        script.src = src
        script.async = true
        script.dataset.dmMapViewer = '1'
        script.addEventListener('load', () => {
            script.dataset.loaded = '1'
            resolve()
        })
        script.addEventListener('error', () => reject(new Error('Developer map viewer script failed to load')))
        document.head.appendChild(script)
    })
}

export const useDeveloperMapViewer = () => {
    const config = useRuntimeConfig()
    const isReady = useState('dm-map-viewer-ready', () => false)

    const endpoint = computed(() => `${config.public.apiUrl}/developer-map/viewer`)

    const ensureLoaded = async () => {
        if (process.server) return

        ;(window as any).dmFrontendConfig = {
            ...(window as any).dmFrontendConfig,
            endpoint: endpoint.value,
        }

        if ((window as any).dmMapViewerHydrate) {
            isReady.value = true
            return
        }

        if (!loadPromise) {
            const ver = Date.now()
            loadPromise = loadScript(`/lib/developer-map-v2/assets/js/frontend/map-viewer.js?ver=${ver}`)
        }

        await loadPromise
        isReady.value = true
    }

    const hydrate = () => {
        if (process.server) return
        if (typeof window === 'undefined') return

        const fn = (window as any).dmMapViewerHydrate
        if (typeof fn === 'function') {
            fn()
            return
        }

        window.dispatchEvent(new Event('dm-map-viewer:hydrate'))
    }

    return {
        isReady,
        endpoint,
        ensureLoaded,
        hydrate,
    }
}

