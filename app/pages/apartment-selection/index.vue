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
              <span class="floor-info">{{ floor.apartments }} bytov</span>
              <span class="floor-status" :class="floor.statusClass">
                {{ floor.statusText }}
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
                <span class="apt-status" :class="apt.statusClass">{{ apt.status }}</span>
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
                <span class="apt-price">od {{ apt.price.toLocaleString() }} €</span>
                <button class="btn btn-outline-dark">Detail</button>
              </div>
            </div>
          </div>
        </div>
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
const selectedFloor = ref(1)
const activeFilter = ref('all')

const floors = [
  { id: 4, name: '4. NP', apartments: 12, statusText: '8 voľných', statusClass: 'available' },
  { id: 3, name: '3. NP', apartments: 12, statusText: '5 voľných', statusClass: 'available' },
  { id: 2, name: '2. NP', apartments: 12, statusText: '3 voľné', statusClass: 'limited' },
  { id: 1, name: '1. NP', apartments: 12, statusText: '2 voľné', statusClass: 'limited' },
]

const filters = [
  { label: 'Všetky', value: 'all' },
  { label: '1-izbové', value: '1' },
  { label: '2-izbové', value: '2' },
  { label: '3-izbové', value: '3' },
  { label: '4-izbové', value: '4' },
]

const apartments = [
  { id: 1, name: 'Byt A-101', layout: '2-izbový', area: 54, floor: 1, price: 145000, status: 'Voľný', statusClass: 'available' },
  { id: 2, name: 'Byt A-102', layout: '3-izbový', area: 72, floor: 1, price: 189000, status: 'Rezervovaný', statusClass: 'reserved' },
  { id: 3, name: 'Byt A-103', layout: '1-izbový', area: 38, floor: 1, price: 98000, status: 'Voľný', statusClass: 'available' },
  { id: 4, name: 'Byt A-201', layout: '2-izbový', area: 56, floor: 2, price: 152000, status: 'Voľný', statusClass: 'available' },
  { id: 5, name: 'Byt A-202', layout: '4-izbový', area: 95, floor: 2, price: 265000, status: 'Predaný', statusClass: 'sold' },
  { id: 6, name: 'Byt A-203', layout: '3-izbový', area: 75, floor: 2, price: 198000, status: 'Voľný', statusClass: 'available' },
]

const displayedApartments = computed(() => {
  if (activeFilter.value === 'all') return apartments
  return apartments.filter(apt => apt.layout.startsWith(activeFilter.value))
})
</script>
