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
        <div class="info-map-embed">
          <DeveloperMapDmMapShortcode :content="mapEmbed" />
        </div>
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
        <DeveloperMapDmMapShortcode :content="pricingEmbed" />
      </div>
    </section>

    <HomeCtaSection />
  </div>
</template>

<script setup lang="ts">
const mapEmbed = '[dm_map map_key="bytovy-dom-a"]'
const pricingEmbed = '[dm_map map_key="cennik-bytovy-dom"]'
</script>

<style scoped>
.info-map-embed {
  width: 100%;
  border-radius: 1rem;
  overflow: hidden;
}
</style>
