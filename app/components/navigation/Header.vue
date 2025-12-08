<template>
  <header class="header">
    <div class="header-container">
      <!-- Logo -->
      <NuxtLink to="/" class="header-logo">
        <div class="logo-placeholder">
          <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="40" height="40" fill="#d4a84b"/>
            <path d="M10 30V15L20 8L30 15V30H22V22H18V30H10Z" fill="#1a2744"/>
          </svg>
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
        <NuxtLink to="/contact" class="mobile-nav-link" @click="closeMenu">Kontakt</NuxtLink>
        <NuxtLink to="/apartment-selection" class="mobile-nav-link btn-gold" @click="closeMenu">Výber bytu</NuxtLink>
      </nav>
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
