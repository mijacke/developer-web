<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-header">
        <h1>Rezidencia Žilina</h1>
        <p>Obnovenie hesla</p>
      </div>
      
      <div v-if="success" class="auth-form">
        <div class="success-message">
          <p>{{ success }}</p>
        </div>
        <NuxtLink to="/login" class="submit-btn" style="margin-top: 1rem;">← Späť na prihlásenie</NuxtLink>
      </div>
      
      <form v-else @submit.prevent="handleSubmit" class="auth-form">
        <p class="auth-info">
          Zadajte svoju emailovú adresu a my vám pošleme odkaz na obnovenie hesla.
        </p>
        
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
        
        <div v-if="error" class="error-message">
          {{ error }}
        </div>
        
        <button type="submit" class="submit-btn" :disabled="loading">
          <span v-if="loading">Odosielam...</span>
          <span v-else>Odoslať odkaz</span>
        </button>
        
        <div class="form-footer">
          <p><NuxtLink to="/login">← Späť na prihlásenie</NuxtLink></p>
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
const email = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  error.value = ''
  loading.value = true
  
  try {
    const response = await $fetch<{ message: string }>(`${config.public.apiUrl}/auth/forgot-password`, {
      method: 'POST',
      body: { email: email.value }
    })
    
    success.value = response.message
  } catch (e: any) {
    error.value = e.response?._data?.message || 'Nepodarilo sa odoslať email.'
  } finally {
    loading.value = false
  }
}
</script>
