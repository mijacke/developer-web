<template>
  <div class="gallery-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>Galéria</span>
      </nav>
    </div>

    <!-- Hero Section -->
    <section class="page-hero">
      <div class="page-hero-bg placeholder-image"></div>
      <div class="page-hero-overlay"></div>
      <div class="page-hero-content">
        <h1 class="page-title">Galéria</h1>
        <p class="page-subtitle">Pozrite si Rezidenciu Žilina v obrazoch</p>
      </div>
    </section>

    <!-- Gallery Content -->
    <section class="gallery-content section">
      <div class="container">
        <!-- Exterior Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Exteriér</h2>
          <p class="gallery-section-desc">
            Vonkajšie priestory rezidencie s príjemnou parkovou úpravou
          </p>
          
          <div class="gallery-grid">
            <div class="gallery-item large">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
          </div>
        </div>

        <!-- Interior Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Interiér</h2>
          <p class="gallery-section-desc">
            Vzorové byty s modernými interiérmi
          </p>
          
          <div class="gallery-grid">
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item large">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
          </div>
        </div>

        <!-- Surroundings Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Okolie</h2>
          <p class="gallery-section-desc">
            Okolité prostredie v Žiline a dostupné služby
          </p>
          
          <div class="gallery-grid">
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
