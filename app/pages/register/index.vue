<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-header">
        <h1>Rezidencia Žilina</h1>
        <p>Registrácia</p>
      </div>
      
      <div v-if="success" class="auth-form">
        <div class="success-message">
          <p>{{ success }}</p>
        </div>
        <NuxtLink to="/login" class="submit-btn" style="margin-top: 1rem;">
          Prihlásiť sa
        </NuxtLink>
      </div>
      
      <form v-else @submit.prevent="handleRegister" class="auth-form">
        <div class="form-group">
          <label for="name">Meno</label>
          <input 
            type="text" 
            id="name" 
            v-model="name" 
            placeholder="Vaše meno" 
            required
            :disabled="loading"
          >
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="email" 
            placeholder="vas@email.com" 
            required
            :disabled="loading"
          >
        </div>
        
        <div class="form-group">
          <label for="password">Heslo</label>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            placeholder="••••••••" 
            required
            :disabled="loading"
          >
        </div>
        
        <div class="form-group">
          <label for="password_confirmation">Potvrdenie hesla</label>
          <input 
            type="password" 
            id="password_confirmation" 
            v-model="passwordConfirmation" 
            placeholder="••••••••" 
            required
            :disabled="loading"
          >
        </div>
        
        <div v-if="error" class="error-message">
          {{ error }}
        </div>
        
        <button type="submit" class="submit-btn" :disabled="loading">
          <span v-if="loading">Registrujem...</span>
          <span v-else>Zaregistrovať sa</span>
        </button>
        
        <div class="form-footer">
          <p>Už máte účet? <NuxtLink to="/login">Prihláste sa</NuxtLink></p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'default',
  middleware: 'guest'
})

const config = useRuntimeConfig()
const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)

const handleRegister = async () => {
  error.value = ''
  
  if (password.value !== passwordConfirmation.value) {
    error.value = 'Heslá sa nezhodujú.'
    return
  }
  
  if (password.value.length < 8) {
    error.value = 'Heslo musí mať minimálne 8 znakov.'
    return
  }
  
  loading.value = true
  
  try {
    await $fetch(`${config.public.apiUrl}/auth/register`, {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      }
    })
    
    success.value = 'Registrácia úspešná! Váš účet čaká na schválenie administrátorom.'
  } catch (e: any) {
    const msg = e.response?._data?.message || e.message || ''
    if (msg.includes('already been taken')) {
      error.value = 'Používateľ s týmto emailom už existuje.'
    } else {
      error.value = msg || 'Nepodarilo sa zaregistrovať.'
    }
  } finally {
    loading.value = false
  }
}
</script>
