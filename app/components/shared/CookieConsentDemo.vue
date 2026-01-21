<template>
  <!-- Demo-only cookie consent UI (no real consent logic yet) -->
  <div class="cookie-demo" aria-label="Cookies demo">
    <button
      type="button"
      class="cookie-demo__floating"
      aria-label="Predvoľby cookies (demo)"
      title="Predvoľby cookies (demo)"
      @click.stop="toggle"
    >
      <img src="~/assets/icons/ui/cookie.svg" alt="" width="22" height="22" />
    </button>

    <div
      v-if="isOpen"
      class="cookie-demo__backdrop"
      aria-hidden="true"
      @click="close"
    />

    <section
      v-if="isOpen"
      class="cookie-demo__panel"
      role="dialog"
      aria-modal="true"
      aria-label="Cookies oznámenie (demo)"
      @click="close"
    >
      <h2 class="cookie-demo__title">Cookies</h2>
      <p class="cookie-demo__description">
        Používame cookies na základné fungovanie webu a na zlepšovanie obsahu.
        Predvoľby súhlasu sú vždy dostupné cez ikonu cookies vľavo dole.
      </p>

      <p class="cookie-demo__links">
        <NuxtLink to="/cookies" class="cookie-demo__link" @click="close">Spracovanie cookies</NuxtLink>
        <span class="cookie-demo__separator">•</span>
        <NuxtLink to="/privacy" class="cookie-demo__link" @click="close">Ochrana súkromia</NuxtLink>
        <span class="cookie-demo__separator">•</span>
        <NuxtLink to="/terms" class="cookie-demo__link" @click="close">Obchodné podmienky</NuxtLink>
      </p>

      <div class="cookie-demo__actions" aria-label="Akcie cookies (demo)">
        <button type="button" class="btn btn-outline-dark cookie-demo__action" @click="close">
          Odmietnuť všetko
        </button>
        <NuxtLink to="/cookies" class="btn btn-outline-dark cookie-demo__action" @click="close">
          Spracovanie Cookies
        </NuxtLink>
        <button type="button" class="btn btn-primary cookie-demo__action" @click="close">
          Prijať všetko
        </button>
      </div>

      <p class="cookie-demo__note">Demo verzia: klik zatvorí lištu, nič sa neukladá.</p>
    </section>
  </div>
</template>

<script setup lang="ts">
const DISMISS_MS = 60 * 60 * 1000
const STORAGE_KEY = 'cookie-demo-dismissed-until'

const isOpen = ref(false)

onMounted(() => {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    const dismissedUntil = raw ? Number.parseInt(raw, 10) : 0
    isOpen.value = !dismissedUntil || Number.isNaN(dismissedUntil) || Date.now() > dismissedUntil
  } catch {
    isOpen.value = true
  }
})

const close = () => {
  try {
    localStorage.setItem(STORAGE_KEY, String(Date.now() + DISMISS_MS))
  } catch {
    // ignore
  }
  isOpen.value = false
}

const toggle = () => {
  if (isOpen.value) {
    close()
    return
  }

  isOpen.value = true
}
</script>
