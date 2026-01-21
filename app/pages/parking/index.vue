<template>
  <div class="parking-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>Parkovanie</span>
      </nav>
    </div>

    <!-- Parking Content -->
    <section class="parking-content section">
      <div class="container">
        <div class="parking-grid">
          <div class="parking-text">
            <h2>Vonkajšie parkovanie</h2>
            <p>
              Rezidencia Žilina ponúka zabezpečené vonkajšie parkovanie 
              s dostatočnou kapacitou pre všetkých rezidentov. Každý byt má 
              možnosť zakúpiť si vlastné parkovacie miesto priamo v areáli 
              projektu.
            </p>
            <p>
              Parkovisko je vybavené moderným vstupným systémom s diaľkovým 
              ovládaním a 24-hodinovým kamerovým dohľadom pre maximálnu 
              bezpečnosť vášho vozidla.
            </p>
          </div>
          <NuxtImg src="/images/parking/parking.png" 
          alt="Vonkajšie parkovanie" 
          class="parking-image" 
          width="1200"
          height="800"
          />
        
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="parking-features section bg-light">
      <div class="container">
        <h2 class="section-title text-center">Výhody parkovania</h2>
        
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <img src="/assets/icons/what-we-offer/materials.svg" alt="Material" width="32" height="32">
            </div>
            <h4>Zabezpečené</h4>
            <p>24/7 kamerový systém a kontrolovaný prístup</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <img src="/assets/icons/what-we-offer/clock.svg" alt="Hodiny" width="32" height="32">
            </div>
            <h4>Dostupné 24/7</h4>
            <p>Nepretržitý prístup k vášmu vozidlu</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <img src="/assets/icons/what-we-offer/energy.svg" alt="Elektrické nabíjanie" width="32" height="32">
            </div>
            <h4>E-nabíjanie</h4>
            <p>Príprava na nabíjanie elektromobilov</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <img src="/assets/icons/what-we-offer/lightbulb.svg" alt="Osvetlenie" width="32" height="32">
            </div>
            <h4>Osvetlenie</h4>
            <p>Kvalitné nočné osvetlenie celého areálu</p>
          </div>
        </div>
      </div>
    </section>


    <!-- Rezidencia Žilina Info Section -->
    <section class="info-section section">
      <div class="container">
        <div class="text-center mb-lg">
          <h1 class="section-title">Rezidencia Žilina</h1>
          <p class="section-subtitle">
            Objavte bezpečné a pohodlné parkovanie. Rezidencia Žilina ponúka komfortné parkovacie miesta priamo v areáli.
          </p>
        </div>
        <div class="info-map-embed">
          <DeveloperMapDmMapShortcode :content="mapEmbed" />
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section class="parking-pricing section">
      <div class="container">
        <div class="text-center mb-lg">
          <h2 class="section-title">Cenník</h2>
          <p class="section-subtitle">
            Pozrite si aktuálny cenník našich bytov a parkovacích miest.
          </p>
        </div>
        <DeveloperMapDmMapShortcode :content="parkingEmbed" />
      </div>
    </section>

    <HomeCtaSection />
  </div>
</template>

<script setup lang="ts">
const mapEmbed = '[dm_map map_key="parkovanie"]'
const parkingEmbed = '[dm_map map_key="cennik-parkovanie"]'

// SEO Meta Tags
useSeoMeta({
  title: 'Parkovanie | Rezidencia Žilina',
  description: 'Bezpečné a pohodlné parkovanie v Rezidencii Žilina - zabezpečené parkovacie miesta s 24/7 kamerovým systémom a prípravou na e-nabíjanie.',
  ogTitle: 'Parkovanie | Rezidencia Žilina',
  ogDescription: 'Zabezpečené parkovacie miesta s 24/7 prístupom a kamerovým systémom.',
  ogImage: '/images/parking/parking.png',
  ogType: 'website'
})
</script>

<style scoped>
.info-map-embed {
  width: 100%;
  border-radius: 1rem;
  overflow: hidden;
}
</style>
