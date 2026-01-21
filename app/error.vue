<script setup lang="ts">
/**
 * Error Page Component
 * Handles 404, 500 and other error pages
 * CSS: app/assets/styles/pages/_error.css
 */

interface NuxtError {
  statusCode: number
  statusMessage?: string
  message?: string
}

const props = defineProps<{
  error: NuxtError
}>()

// SEO Meta for error pages
useSeoMeta({
  title: () => `${props.error.statusCode} | Rezidencia Žilina`,
  description: 'Stránka nebola nájdená. Vráťte sa na úvodnú stránku Rezidencia Žilina.',
  robots: 'noindex, nofollow'
})

// Error content based on status code
const errorContent = computed(() => {
  const code = props.error.statusCode

  if (code === 404) {
    return {
      title: 'Stránka nenájdená',
      description: 'Ľutujeme, ale stránka ktorú hľadáte neexistuje alebo bola presunutá.',
      icon: 'search'
    }
  }

  if (code === 500) {
    return {
      title: 'Chyba servera',
      description: 'Ospravedlňujeme sa, nastala chyba na serveri. Skúste to prosím neskôr.',
      icon: 'alert'
    }
  }

  if (code === 403) {
    return {
      title: 'Prístup zamietnutý',
      description: 'Nemáte oprávnenie na zobrazenie tejto stránky.',
      icon: 'lock'
    }
  }

  return {
    title: 'Niečo sa pokazilo',
    description: props.error.message || 'Nastala neočakávaná chyba.',
    icon: 'alert'
  }
})

// Handle navigation
const handleError = () => clearError({ redirect: '/' })

// Suggested pages for navigation
const suggestedPages = [
  { name: 'Úvod', path: '/' },
  { name: 'O projekte', path: '/about-project' },
  { name: 'Výber bytu', path: '/apartment-selection' },
  { name: 'Kontakt', path: '/contact' }
]
</script>

<template>
  <div class="error-page">
    <div class="error-content">
      <!-- Logo -->
      <div class="error-logo">
        <NuxtLink to="/" class="error-logo-link">
          <NuxtImg
            src="/images/home/logo.png"
            alt="Rezidencia Žilina"
            class="error-logo-img"
            width="180"
            height="48"
          />
        </NuxtLink>
      </div>

      <!-- Error Code -->
      <div class="error-code">{{ error.statusCode }}</div>

      <!-- Error Title -->
      <h1 class="error-title">{{ errorContent.title }}</h1>

      <!-- Error Description -->
      <p class="error-description">{{ errorContent.description }}</p>

      <!-- Action Buttons -->
      <div class="error-actions">
        <button @click="handleError" class="error-btn error-btn--primary">
          <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9,22 9,12 15,12 15,22"/>
          </svg>
          Späť na úvod
        </button>

        <NuxtLink to="/contact" class="error-btn error-btn--secondary">
          <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          Kontaktujte nás
        </NuxtLink>
      </div>

      <!-- Suggested Pages -->
      <div class="error-suggestions">
        <div class="error-suggestions-title">Môžete navštíviť</div>
        <ul class="error-suggestions-list">
          <li v-for="page in suggestedPages" :key="page.path">
            <NuxtLink :to="page.path">{{ page.name }}</NuxtLink>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
