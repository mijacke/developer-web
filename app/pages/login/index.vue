<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-header">
        <h1>Rezidencia Žilina</h1>
        <p>Administrácia</p>
      </div>
      
      <form @submit.prevent="handleLogin" class="auth-form">
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
        
        <div v-if="error" class="error-message">
          {{ error }}
        </div>
        
        <button type="submit" class="submit-btn" :disabled="loading">
          <span v-if="loading">Prihlasujem...</span>
          <span v-else>Prihlásiť sa</span>
        </button>
        
        <div class="form-footer">
          <p><NuxtLink to="/forgot-password">Zabudli ste heslo?</NuxtLink></p>
          <p>Nemáte účet? <NuxtLink to="/register">Zaregistrujte sa</NuxtLink></p>
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

const { login } = useAuth()
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const route = useRoute()

const handleLogin = async () => {
  error.value = ''
  loading.value = true
  
  try {
    await login({
      email: email.value,
      password: password.value
    })
    
    // Redirect to intended page or admin
    const redirectPath = route.query.redirect as string || '/admin'
    navigateTo(redirectPath)
  } catch (e: any) {
    console.error(e)
    const backendMsg = e.response?._data?.message || e.message || ''
    
    if (backendMsg.includes('blocked')) {
      error.value = 'Váš účet bol zablokovaný.'
    } else if (backendMsg.includes('pending approval')) {
      error.value = 'Váš účet čaká na schválenie administrátorom.'
    } else if (backendMsg.includes('incorrect')) {
      error.value = 'Nesprávne meno alebo heslo.'
    } else {
      error.value = backendMsg || 'Nesprávne meno alebo heslo.'
    }
  } finally {
    loading.value = false
  }
}
</script>
