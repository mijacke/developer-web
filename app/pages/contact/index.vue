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

    <!-- Hero Section -->
    <section class="page-hero">
      <div class="page-hero-bg placeholder-image"></div>
      <div class="page-hero-overlay"></div>
      <div class="page-hero-content">
        <h1 class="page-title">Kontaktujte nás</h1>
        <p class="page-subtitle">Radi odpovieme na vaše otázky</p>
      </div>
    </section>

    <!-- Contact Cards -->
    <section class="contact-cards section">
      <div class="container">
        <div class="cards-grid">
          <div class="contact-card">
            <div class="card-avatar placeholder-image"></div>
            <div class="card-info">
              <h4>Mario Lassu</h4>
              <p class="card-role">Obchodný manažér</p>
              <div class="card-contact">
                <a href="tel:+421900111222">+421 900 111 222</a>
                <a href="mailto:mario.lassu@developer.sk">mario.lassu@developer.sk</a>
              </div>
            </div>
          </div>

          <div class="contact-card">
            <div class="card-avatar placeholder-image"></div>
            <div class="card-info">
              <h4>Mario Lassu</h4>
              <p class="card-role">Projektový manažér</p>
              <div class="card-contact">
                <a href="tel:+421900333444">+421 900 333 444</a>
                <a href="mailto:mario.lassu@developer.sk">mario.lassu@developer.sk</a>
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

          <form class="contact-form" @submit.prevent="handleSubmit" novalidate>
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
                >
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
const config = useRuntimeConfig()
const API_URL = config.public.apiUrl

// Form data
const form = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  message: '',
  gdpr: false
})

// Validation errors
const errors = ref<Record<string, string>>({})

// Form state
const isSubmitting = ref(false)
const formMessage = ref('')
const formMessageType = ref<'success' | 'error'>('success')

// Email regex pattern
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
// Slovak phone pattern: +421XXXXXXXXX or 09XXXXXXXX
const phonePattern = /^(\+421|0)[0-9]{9}$/

// Validate single field - CLIENT-SIDE VALIDATION
const validateField = (field: string): boolean => {
  let isValid = true
  
  switch (field) {
    case 'firstName':
      if (!form.value.firstName.trim()) {
        errors.value.firstName = 'Meno je povinné'
        isValid = false
      } else if (form.value.firstName.length < 2) {
        errors.value.firstName = 'Meno musí mať aspoň 2 znaky'
        isValid = false
      } else {
        delete errors.value.firstName
      }
      break
      
    case 'lastName':
      if (!form.value.lastName.trim()) {
        errors.value.lastName = 'Priezvisko je povinné'
        isValid = false
      } else if (form.value.lastName.length < 2) {
        errors.value.lastName = 'Priezvisko musí mať aspoň 2 znaky'
        isValid = false
      } else {
        delete errors.value.lastName
      }
      break
      
    case 'email':
      if (!form.value.email.trim()) {
        errors.value.email = 'E-mail je povinný'
        isValid = false
      } else if (!emailPattern.test(form.value.email)) {
        errors.value.email = 'Zadajte platný e-mail'
        isValid = false
      } else {
        delete errors.value.email
      }
      break
      
    case 'phone':
      // Phone is optional, but if provided must be valid
      if (form.value.phone.trim() && !phonePattern.test(form.value.phone.replace(/\s/g, ''))) {
        errors.value.phone = 'Telefón musí byť v formáte +421XXXXXXXXX'
        isValid = false
      } else {
        delete errors.value.phone
      }
      break
      
    case 'message':
      if (!form.value.message.trim()) {
        errors.value.message = 'Správa je povinná'
        isValid = false
      } else if (form.value.message.length < 10) {
        errors.value.message = 'Správa musí mať aspoň 10 znakov'
        isValid = false
      } else {
        delete errors.value.message
      }
      break
      
    case 'gdpr':
      if (!form.value.gdpr) {
        errors.value.gdpr = 'Musíte súhlasiť so spracovaním údajov'
        isValid = false
      } else {
        delete errors.value.gdpr
      }
      break
  }
  
  return isValid
}

// Validate all fields
const validateForm = (): boolean => {
  const fields = ['firstName', 'lastName', 'email', 'phone', 'message', 'gdpr']
  let isValid = true
  
  fields.forEach(field => {
    if (!validateField(field)) {
      isValid = false
    }
  })
  
  return isValid
}

// Submit form
const handleSubmit = async () => {
  // Clear previous messages
  formMessage.value = ''
  
  // Client-side validation
  if (!validateForm()) {
    formMessage.value = 'Prosím opravte chyby vo formulári'
    formMessageType.value = 'error'
    return
  }
  
  isSubmitting.value = true
  
  try {
    // Send to Laravel API for server-side validation
    const response = await fetch(`${API_URL}/contact`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        ...form.value,
        phone: form.value.phone.replace(/\s/g, '') // Remove spaces from phone
      })
    })
    
    const data = await response.json()
    
    if (!response.ok) {
      // Server validation errors
      if (data.errors) {
        // Map server errors to form fields
        Object.keys(data.errors).forEach(key => {
          errors.value[key] = data.errors[key][0]
        })
      }
      formMessage.value = 'Chyba pri odosielaní formulára'
      formMessageType.value = 'error'
      return
    }
    
    // Success
    formMessage.value = data.message
    formMessageType.value = 'success'
    
    // Reset form
    form.value = {
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      message: '',
      gdpr: false
    }
    errors.value = {}
    
  } catch (error) {
    formMessage.value = 'Chyba pripojenia k serveru'
    formMessageType.value = 'error'
  } finally {
    isSubmitting.value = false
  }
}
</script>
