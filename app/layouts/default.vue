<template>
  <div class="layout">
    <Header />
    <main class="main-content">
      <slot />
    </main>
    <Footer />
    <CookieConsentDemo />
  </div>
</template>

<script setup lang="ts">
import Header from '~/components/navigation/Header.vue'
import Footer from '~/components/footer/Footer.vue'
import CookieConsentDemo from '~/components/shared/CookieConsentDemo.vue'
</script>
