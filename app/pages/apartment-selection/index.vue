<template>
  <div class="apartment-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>Výber bytu</span>
      </nav>
    </div>

    <!-- Rezidencia Žilina Info Section -->
    <section class="info-section section">
      <div class="container">
        <div class="text-center mb-lg">
          <h1 class="section-title">Rezidencia Žilina</h1>
          <p class="section-subtitle">
            Objavte moderné bývanie v srdci prírody. Rezidencia Žilina spája komfort mestského života s pokojom zelene.
          </p>
        </div>
        <NuxtImg 
          src="/images/apartments/cad-building.png" 
          alt="Rezidencia Žilina vizualizácia" 
          class="info-image" 
          style="width: 100%; height: 400px; object-fit: cover; border-radius: 1rem;" 
        />
      </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section section">
      <div class="container">
        <div class="text-center mb-lg">
          <h2 class="section-title">Cenník</h2>
          <p class="section-subtitle">
            Pozrite si aktuálny cenník našich bytov a parkovacích miest.
          </p>
        </div>
        <NuxtImg 
          src="/images/apartments/cad-building.png" 
          alt="Cenník vizualizácia" 
          class="pricing-image" 
          style="width: 100%; height: 300px; object-fit: cover; border-radius: 1rem;" 
        />
      </div>
    </section>

    <!-- Apartments List -->
    <section class="apartments-section section bg-light">
      <div class="container">
        <h2 class="section-title text-center">Dostupné byty</h2>
        
        <!-- Loading/Error states -->
        <div v-if="loading" class="text-center py-lg">Načítavam byty zo servera...</div>
        <div v-else-if="error" class="text-center py-lg text-muted">{{ error }}</div>
        
        <template v-else>
          <div class="filter-bar">
            <button 
              v-for="filter in filters" 
              :key="filter.value"
              class="filter-btn"
              :class="{ active: activeFilter === filter.value }"
              @click="activeFilter = filter.value"
            >
              {{ filter.label }}
            </button>
          </div>

          <div class="apartments-grid">
            <div class="apartment-card" v-for="apt in displayedApartments" :key="apt.id">
              <div class="apt-image placeholder-image"></div>
              <div class="apt-content">
                <div class="apt-header">
                  <h4>{{ apt.name }}</h4>
                  <span class="apt-status" :class="apt.status">{{ statusLabels[apt.status] }}</span>
                </div>
                <div class="apt-details">
                  <div class="apt-detail">
                    <span class="detail-label">Dispozícia</span>
                    <span class="detail-value">{{ apt.layout }}</span>
                  </div>
                  <div class="apt-detail">
                    <span class="detail-label">Plocha</span>
                    <span class="detail-value">{{ apt.area }} m²</span>
                  </div>
                  <div class="apt-detail">
                    <span class="detail-label">Podlažie</span>
                    <span class="detail-value">{{ apt.floor }}. NP</span>
                  </div>
                </div>
                <div class="apt-footer">
                  <span class="apt-price">od {{ Number(apt.price).toLocaleString() }} €</span>
                  <button class="btn btn-outline-dark">Detail</button>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </section>

    <!-- Legend -->
    <section class="legend-section section">
      <div class="container">
        <div class="legend">
          <div class="legend-item">
            <span class="legend-color available"></span>
            <span>Voľný</span>
          </div>
          <div class="legend-item">
            <span class="legend-color reserved"></span>
            <span>Rezervovaný</span>
          </div>
          <div class="legend-item">
            <span class="legend-color sold"></span>
            <span>Predaný</span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const API_URL = config.public.apiUrl

interface Apartment {
  id: number
  name: string
  layout: string
  area: number
  floor: number
  price: number
  status: 'available' | 'reserved' | 'sold'
}

const apartments = ref<Apartment[]>([])
const loading = ref(true)
const error = ref('')

const activeFilter = ref('all')

const statusLabels: Record<string, string> = {
  available: 'Voľný',
  reserved: 'Rezervovaný',
  sold: 'Predaný'
}



const filters = [
  { label: 'Všetky', value: 'all' },
  { label: '1-izbové', value: '1' },
  { label: '2-izbové', value: '2' },
  { label: '3-izbové', value: '3' },
  { label: '4-izbové', value: '4' },
]

// Fetch apartments from API
const fetchApartments = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await fetch(`${API_URL}/apartments`)
    if (!response.ok) throw new Error('Chyba pri načítaní')
    apartments.value = await response.json()
  } catch (e) {
    error.value = 'Nepodarilo sa načítať byty. Skontrolujte či beží Laravel server.'
  } finally {
    loading.value = false
  }
}

// Filter apartments
const displayedApartments = computed(() => {
  let filtered = apartments.value
  
  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(apt => apt.layout.startsWith(activeFilter.value))
  }
  
  return filtered
})

// Floor helpers


// Initialize
onMounted(() => {
  fetchApartments()
})
</script>
