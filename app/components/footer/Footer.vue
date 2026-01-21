<template>
  <footer class="footer">
    <div class="footer-container">
      <!-- Logo & Social -->
      <div class="footer-brand">
        <NuxtLink to="/" class="footer-logo">
          <div class="logo-wrapper">
            <NuxtImg
              src="/images/favicon.png"
              alt="Rezidencia Žilina"
              width="40"
              height="40"
              class="logo-image"
            />
          </div>
          <div class="logo-text">
            <span class="logo-title">REZIDENCIA</span>
            <span class="logo-subtitle">ŽILINA</span>
          </div>
        </NuxtLink>
        
        <p class="footer-description">
          Rezidencia Žilina ponúka moderné bývanie v atraktívnej lokalite s výbornou dostupnosťou. Kvalitné byty, parkovanie a kompletná občianska vybavenosť na dosah ruky.
        </p>
        
        <div class="social-links">
          <a href="#" class="social-link" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
            </svg>
          </a>
          <a href="#" class="social-link" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Information Links -->
      <div class="footer-section">
        <h4 class="footer-title">Informácie</h4>
        <nav class="footer-links">
          <NuxtLink to="/about-project" class="footer-link">O projekte</NuxtLink>
          <NuxtLink to="/locality" class="footer-link">Lokalita</NuxtLink>
          <NuxtLink to="/gallery" class="footer-link">Galéria</NuxtLink>
          <NuxtLink to="/contact" class="footer-link">Kontakt</NuxtLink>
        </nav>
      </div>

      <!-- Documents Links -->
      <div class="footer-section">
        <h4 class="footer-title">Dokumenty</h4>
        <nav class="footer-links">
          <NuxtLink to="/privacy" class="footer-link">Ochrana súkromia</NuxtLink>
          <NuxtLink to="/terms" class="footer-link">Obchodné podmienky</NuxtLink>
          <NuxtLink to="/cookies" class="footer-link">Spracovanie Cookies</NuxtLink>
        </nav>
      </div>

      <!-- Developer Info -->
      <div class="footer-section">
        <h4 class="footer-title">Developer</h4>
        <div class="footer-contact">
          <p class="contact-name">Rezidencia Žilina</p>
          <p class="contact-info">
            <a href="tel:+421900000000">+421 900 000 000</a>
          </p>
          <p class="contact-info">
            <a href="mailto:info@rezidenciazilina.sk">info@rezidenciazilina.sk</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
      <div class="footer-container footer-bottom-inner">
        <p class="copyright">Copyright © {{ new Date().getFullYear() }} <strong>Rezidencia Žilina</strong> – Všetky práva vyhradené</p>
        <button class="scroll-to-top" @click="scrollToTop" aria-label="Návrat hore">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 15l-6-6-6 6"/>
          </svg>

        </button>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}
</script>
