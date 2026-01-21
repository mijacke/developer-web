<template>
  <div class="contact-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>Kontakt</span>
      </nav>
    </div>

    <!-- Contact Cards -->
    <section class="contact-cards section">
      <div class="container">
        <div class="cards-grid">
          <div class="contact-card">
            <NuxtImg src="/images/home/mario-lassu.png" alt="Mário Laššú" class="card-avatar" />
            <div class="card-info">
              <h4>Mário Laššú</h4>
              <p class="card-role">Obchodný manažér</p>
              <div class="card-contact">
                <a href="tel:+421900111222">+421 900 111 222</a>
                <a href="mailto:mario.lassu@developer.sk">mario.lassu@developer.sk</a>
              </div>
            </div>
          </div>

          <div class="contact-card">
            <NuxtImg src="/images/home/mario-lassu.png" alt="Mário Laššú" class="card-avatar" />
            <div class="card-info">
              <h4>Mário Laššú</h4>
              <p class="card-role">Projektový manažér</p>
              <div class="card-contact">
                <a href="tel:+421900333444">+421 900 333 444</a>
                <a href="mailto:lassu.mario@developer.sk">lassu.mario@developer.sk</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section section bg-light">
      <div class="container">
        <div class="form-grid">
          <div class="form-info">
            <h2>Napíšte nám</h2>
            <p>
              Máte záujem o byt v Rezidencii Žilina? Vyplňte kontaktný formulár 
              a náš tím vás bude kontaktovať v čo najkratšom čase.
            </p>
            
            <div class="info-items">
              <div class="info-item">
                <div class="info-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                  </svg>
                </div>
                <div>
                  <strong>Adresa</strong>
                  <p>Žilina, Slovensko</p>
                </div>
              </div>

              <div class="info-item">
                <div class="info-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                  </svg>
                </div>
                <div>
                  <strong>Telefón</strong>
                  <p>+421 900 000 000</p>
                </div>
              </div>

              <div class="info-item">
                <div class="info-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                  </svg>
                </div>
                <div>
                  <strong>E-mail</strong>
                  <p>info@developer.sk</p>
                </div>
              </div>
            </div>
          </div>

          <form class="contact-form" @submit.prevent="handleSubmit" novalidate> <!-- Vlastný ts logika klient validacie formulara-->
            <!-- Success/Error Messages -->
            <div v-if="formMessage" class="form-alert" :class="formMessageType">
              {{ formMessage }}
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="firstName">Meno *</label>
                <input 
                  v-model="form.firstName" 
                  type="text" 
                  id="firstName" 
                  placeholder="Vaše meno"
                  :class="{ 'input-error': errors.firstName }"
                  @blur="validateField('firstName')"
                > <!-- blur na validaciu pola-->
                <span v-if="errors.firstName" class="error-text">{{ errors.firstName }}</span>
              </div>
              <div class="form-group">
                <label for="lastName">Priezvisko *</label>
                <input 
                  v-model="form.lastName" 
                  type="text" 
                  id="lastName" 
                  placeholder="Vaše priezvisko"
                  :class="{ 'input-error': errors.lastName }"
                  @blur="validateField('lastName')"
                >
                <span v-if="errors.lastName" class="error-text">{{ errors.lastName }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="email">E-mail *</label>
              <input 
                v-model="form.email" 
                type="email" 
                id="email" 
                placeholder="vas@email.sk"
                :class="{ 'input-error': errors.email }"
                @blur="validateField('email')"
              >
              <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
            </div>

            <div class="form-group">
              <label for="phone">Telefón</label>
              <input 
                v-model="form.phone" 
                type="tel" 
                id="phone" 
                placeholder="+421 900 000 000"
                :class="{ 'input-error': errors.phone }"
                @blur="validateField('phone')"
              >
              <span v-if="errors.phone" class="error-text">{{ errors.phone }}</span>
            </div>

            <div class="form-group">
              <label for="message">Správa *</label>
              <textarea 
                v-model="form.message" 
                id="message" 
                rows="5" 
                placeholder="Vaša správa..."
                :class="{ 'input-error': errors.message }"
                @blur="validateField('message')"
              ></textarea>
              <span v-if="errors.message" class="error-text">{{ errors.message }}</span>
            </div>

            <div class="form-group checkbox-group">
              <input 
                v-model="form.gdpr" 
                type="checkbox" 
                id="gdpr"
                :class="{ 'input-error': errors.gdpr }"
              >
              <label for="gdpr">
                Súhlasím so spracovaním osobných údajov *
              </label>
            </div>
            <span v-if="errors.gdpr" class="error-text">{{ errors.gdpr }}</span>

            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
              {{ isSubmitting ? 'Odosielam...' : 'Odoslať správu' }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const {
  form,
  errors,
  isSubmitting,
  formMessage,
  formMessageType,
  validateField,
  handleSubmit
} = useContactForm()

// SEO Meta Tags
useSeoMeta({
  title: 'Kontakt | Rezidencia Žilina',
  description: 'Kontaktujte nás ohľadom bytov v Rezidencii Žilina. Náš tím vám rád poradí s výberom bytu a zodpovie všetky vaše otázky.',
  ogTitle: 'Kontakt | Rezidencia Žilina',
  ogDescription: 'Kontaktujte náš tím a dozveďte sa viac o bytoch v Rezidencii Žilina.',
  ogImage: '/images/home/hero.png',
  ogType: 'website'
})
</script>
