<template>
  <section class="standards-section section" aria-label="Štandardy">
    <div class="container">
      <div class="standards-header">
        <p class="standards-kicker">ŠTANDARDY</p>
        <h2 class="standards-title">Nadčasové. Funkčné. Tvoje.</h2>
      </div>
    </div>

    <div ref="splideRoot" class="standards-splide splide" aria-label="Štandardy">
      <div class="splide__track">
        <ul class="splide__list">
          <li v-for="slide in slides" :key="slide.src" class="splide__slide">
            <figure class="standards-slide">
              <NuxtImg
                :src="slide.src"
                :alt="slide.alt"
                width="1600"
                height="1000"
                loading="lazy"
                class="standards-image"
              />
              <figcaption class="standards-caption">{{ slide.caption }}</figcaption>
            </figure>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  slides: Array<{ src: string; alt: string; caption: string }>
}>()

const splideRoot = ref<HTMLElement | null>(null)
const splideInstance = shallowRef<any>(null)
const { ensureLoaded: ensureSplideLoaded } = useSplide()

onMounted(async () => {
  await ensureSplideLoaded()
  await nextTick()

  const Splide = (window as any).Splide
  if (!Splide || !splideRoot.value) {
    return
  }

  splideInstance.value?.destroy?.()

  splideInstance.value = new Splide(splideRoot.value, {
    type: 'loop',
    padding: '20%',
    gap: '4rem',
    perPage: 1,
    focus: 'center',
    breakpoints: {
      1200: { padding: '15%', gap: '2rem' },
      900: { padding: '10%', gap: '1.5rem' },
      600: { padding: '2rem', gap: '1rem' },
    },
  })

  splideInstance.value.mount()
})

onBeforeUnmount(() => {
  splideInstance.value?.destroy?.()
})
</script>
