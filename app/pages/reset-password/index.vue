<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-header">
        <h1>Rezidencia Žilina</h1>
        <p>Nastavenie nového hesla</p>
      </div>
      
      <div v-if="success" class="auth-form">
        <div class="success-message">
          <p>{{ success }}</p>
        </div>
        <NuxtLink to="/login" class="submit-btn" style="margin-top: 1rem;">
          Prihlásiť sa
        </NuxtLink>
      </div>
      
      <form v-else @submit.prevent="handleSubmit" class="auth-form">
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
          <label for="password">Nové heslo</label>
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
          <span v-if="loading">Ukladám...</span>
          <span v-else>Nastaviť heslo</span>
        </button>
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
const route = useRoute()

const email = ref((route.query.email as string) || '')
const password = ref('')
const passwordConfirmation = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)

const token = computed(() => route.query.token as string)

const handleSubmit = async () => {
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
    await $fetch(`${config.public.apiUrl}/auth/reset-password`, {
      method: 'POST',
      body: {
        token: token.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      }
    })
    
    success.value = 'Heslo bolo úspešne zmenené.'
  } catch (e: any) {
    const msg = e.response?._data?.message || e.message || ''
    if (msg.includes('invalid') || msg.includes('token')) {
      error.value = 'Neplatný alebo expirovaný odkaz. Požiadajte o nový.'
    } else {
      error.value = msg || 'Nepodarilo sa zmeniť heslo.'
    }
  } finally {
    loading.value = false
  }
}
</script>
