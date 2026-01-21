<template>
  <header class="header">
    <div class="header-container">
      <!-- Logo -->
      <NuxtLink to="/" class="header-logo">
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

      <!-- Desktop Navigation -->
      <nav class="header-nav">
        <NuxtLink to="/" class="nav-link" :class="{ active: $route.path === '/' }">
          Úvod
        </NuxtLink>
        <NuxtLink to="/about-project" class="nav-link" :class="{ active: $route.path === '/about-project' }">
          O projekte
        </NuxtLink>
        <NuxtLink to="/locality" class="nav-link" :class="{ active: $route.path === '/locality' }">
          Lokalita
        </NuxtLink>
        <NuxtLink to="/gallery" class="nav-link" :class="{ active: $route.path === '/gallery' }">
          Galéria
        </NuxtLink>
        <NuxtLink to="/parking" class="nav-link" :class="{ active: $route.path === '/parking' }">
          Parkovanie
        </NuxtLink>
      </nav>

      <!-- Action Buttons -->
      <div class="header-actions">
        <NuxtLink to="/login" class="btn btn-text">
          Prihlásenie
        </NuxtLink>
        <NuxtLink to="/contact" class="btn btn-outline">
          Kontakt
        </NuxtLink>
        <NuxtLink to="/apartment-selection" class="btn btn-primary">
          Výber bytu
        </NuxtLink>
      </div>

      <!-- Mobile Menu Button -->
      <button class="mobile-menu-btn" @click="toggleMenu" :class="{ active: isMenuOpen }">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" :class="{ open: isMenuOpen }">
      <nav class="mobile-nav-links">
        <NuxtLink to="/" class="mobile-nav-link" @click="closeMenu">Úvod</NuxtLink>
        <NuxtLink to="/about-project" class="mobile-nav-link" @click="closeMenu">O projekte</NuxtLink>
        <NuxtLink to="/locality" class="mobile-nav-link" @click="closeMenu">Lokalita</NuxtLink>
        <NuxtLink to="/gallery" class="mobile-nav-link" @click="closeMenu">Galéria</NuxtLink>
        <NuxtLink to="/parking" class="mobile-nav-link" @click="closeMenu">Parkovanie</NuxtLink>
      </nav>
      
      <!-- Action Buttons Section -->
      <div class="mobile-nav-actions">
        <NuxtLink to="/contact" class="mobile-btn outline" @click="closeMenu">Kontakt</NuxtLink>
        <NuxtLink to="/login" class="mobile-btn outline" @click="closeMenu">Prihlásenie</NuxtLink>
        <NuxtLink to="/apartment-selection" class="mobile-btn primary" @click="closeMenu">Výber bytu</NuxtLink>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
const isMenuOpen = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}
</script>
