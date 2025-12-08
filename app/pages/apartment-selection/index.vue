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

    <!-- Hero Section -->
    <section class="page-hero">
      <div class="page-hero-bg placeholder-image"></div>
      <div class="page-hero-overlay"></div>
      <div class="page-hero-content">
        <h1 class="page-title">Výber bytu</h1>
        <p class="page-subtitle">Nájdite si svoj vysnívaný domov v Rezidencii Žilina</p>
      </div>
    </section>

    <!-- Floor Selection -->
    <section class="floor-selection section">
      <div class="container">
        <div class="selection-intro text-center">
          <h2>Vyberte si podlažie</h2>
          <p class="intro-text">
            Kliknite na podlažie pre zobrazenie dostupných bytov
          </p>
        </div>

        <div class="building-view">
          <div class="floor-item" v-for="floor in floors" :key="floor.id">
            <button 
              class="floor-btn" 
              :class="{ active: selectedFloor === floor.id }"
              @click="selectedFloor = floor.id"
            >
              <span class="floor-number">{{ floor.name }}</span>
              <span class="floor-info">{{ getFloorApartmentCount(floor.id) }} bytov</span>
              <span class="floor-status" :class="getFloorStatusClass(floor.id)">
                {{ getFloorAvailableCount(floor.id) }} voľných
              </span>
            </button>
          </div>
        </div>

        <!-- Building Image -->
        <div class="building-image-container">
          <div class="building-image placeholder-image">
            <span class="building-text">Vizualizácia Rezidencia Žilina</span>
          </div>
        </div>
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
const selectedFloor = ref(1)
const activeFilter = ref('all')

const statusLabels: Record<string, string> = {
  available: 'Voľný',
  reserved: 'Rezervovaný',
  sold: 'Predaný'
}

const floors = [
  { id: 4, name: '4. NP' },
  { id: 3, name: '3. NP' },
  { id: 2, name: '2. NP' },
  { id: 1, name: '1. NP' },
]

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
const getFloorApartmentCount = (floorId: number) => {
  return apartments.value.filter(a => a.floor === floorId).length
}

const getFloorAvailableCount = (floorId: number) => {
  return apartments.value.filter(a => a.floor === floorId && a.status === 'available').length
}

const getFloorStatusClass = (floorId: number) => {
  const available = getFloorAvailableCount(floorId)
  if (available > 3) return 'available'
  if (available > 0) return 'limited'
  return 'sold'
}

// Initialize
onMounted(() => {
  fetchApartments()
})
</script>
