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

        <!-- Construction Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Výstavba</h2>
          <p class="gallery-section-desc">
            Sledujte priebeh výstavby rezidencie
          </p>
          
          <div class="gallery-grid">
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
            <div class="gallery-item">
              <div class="gallery-image placeholder-image"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3D Visualization Video Section -->
    <section class="gallery-video-section section">
      <div class="container">
        <div class="video-header text-center">
          <h2 class="video-title">3D Vizualizácia</h2>
          <p class="video-description">
            Virtuálna prehliadka rezidencie a jej okolia
          </p>
        </div>
        <div class="video-wrapper">
          <div class="video-container">
            <div class="video-placeholder placeholder-image">
              <div class="video-play-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8 5v14l11-7z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <HomeCtaSection />
  </div>
</template>
