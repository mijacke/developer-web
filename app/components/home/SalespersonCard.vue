<template>
  <div class="salesperson-content">
    <h3 class="salesperson-heading">Kontakt na predajcu</h3>
    <div class="salesperson-card">
      <div class="salesperson-photo">
        <img v-if="photo" :src="photo" :alt="name" />
      </div>
      <div class="salesperson-info">
        <h4 class="salesperson-name">{{ name }}</h4>
        <span class="salesperson-role">{{ role }}</span>
        <div class="salesperson-contact">
          <a :href="`tel:${phone.replace(/\s/g, '')}`" class="contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            {{ phone }}
          </a>
          <a :href="`mailto:${email}`" class="contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="16" x="2" y="4" rx="2"/>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
            {{ email }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  name?: string
  role?: string
  phone?: string
  email?: string
  photo?: string
}

withDefaults(defineProps<Props>(), {
  name: 'Meno Priezvisko',
  role: 'Predajca',
  phone: '+421 900 000 000',
  email: 'info@rezidenciazilina.sk',
  photo: ''
})
</script>
