<template>
  <div class="locality-page">
    <!-- Hero Section - New Style -->
    <section class="locality-hero section">
      <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumbs">
          <NuxtLink to="/">Home</NuxtLink>
          <span class="breadcrumbs-separator">/</span>
          <span>Lokalita</span>
        </nav>

        <!-- Hero Content -->
        <div class="locality-hero-content">
          <h1 class="locality-title">Lokalita</h1>
          <p class="locality-subtitle">
            <strong>Objavte moderné bývanie v srdci prírody.</strong> Rezidencia Arborétum spája komfort mestského života s pokojom zelene.
          </p>
        </div>

        <!-- Panoramic Image -->
        <div class="locality-panorama">
          <!-- Placeholder for panoramic image -->
        </div>
      </div>
    </section>

    <!-- Amenities Grid Section -->
    <SharedAmenitiesGrid />

    <!-- Google Maps Section -->
    <section class="locality-map-section">
      <div class="container">
        <div class="locality-map-placeholder"></div>
      </div>
    </section>

    <!-- Locality Content -->
    <section class="locality-content section">
      <div class="container">
        <div class="locality-intro text-center">
          <h2>Všetko na dosah ruky</h2>
          <p class="intro-text">
            Rezidencia Žilina sa nachádza v atraktívnej lokalite, ktorá ponúka 
            ideálnu kombináciu pokoja a dostupnosti všetkých potrebných služieb.
          </p>
        </div>

        <div class="amenities-grid">
          <div class="amenity-card">
            <div class="amenity-image placeholder-image"></div>
            <div class="amenity-content">
              <h4>Školy a škôlky</h4>
              <p>Do 5 minút pešo</p>
            </div>
          </div>

          <div class="amenity-card">
            <div class="amenity-image placeholder-image"></div>
            <div class="amenity-content">
              <h4>Aupark Žilina</h4>
              <p>Do 3 minút autom</p>
            </div>
          </div>

          <div class="amenity-card">
            <div class="amenity-image placeholder-image"></div>
            <div class="amenity-content">
              <h4>Zdravotnícke zariadenie</h4>
              <p>Do 10 minút pešo</p>
            </div>
          </div>

          <div class="amenity-card">
            <div class="amenity-image placeholder-image"></div>
            <div class="amenity-content">
              <h4>Autobusová zastávka</h4>
              <p>Do 2 minút pešo</p>
            </div>
          </div>

          <div class="amenity-card">
            <div class="amenity-image placeholder-image"></div>
            <div class="amenity-content">
              <h4>Park a zeleň</h4>
              <p>Priamo pri rezidencii</p>
            </div>
          </div>

          <div class="amenity-card">
            <div class="amenity-image placeholder-image"></div>
            <div class="amenity-content">
              <h4>Športoviská</h4>
              <p>Do 5 minút pešo</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section -->
    <section class="map-section section bg-light">
      <div class="container">
        <h2 class="section-title text-center">Kde nás nájdete</h2>
        <div class="map-placeholder placeholder-image">
          <span class="map-text">Mapa lokality - Žilina</span>
        </div>
      </div>
    </section>

    <!-- Transport Section -->
    <section class="transport-section section">
      <div class="container">
        <h2 class="section-title text-center">Dopravné spojenie</h2>
        <div class="transport-grid">
          <div class="transport-item">
            <div class="transport-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="3" width="15" height="13"/>
                <polygon points="16,8 20,8 23,11 23,16 16,16 16,8"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
            <h4>Autom</h4>
            <p>Centrum Žiliny do 10 min</p>
          </div>

          <div class="transport-item">
            <div class="transport-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <line x1="3" y1="9" x2="21" y2="9"/>
                <line x1="9" y1="21" x2="9" y2="9"/>
              </svg>
            </div>
            <h4>MHD</h4>
            <p>Zastávka 2 min pešo</p>
          </div>

          <div class="transport-item">
            <div class="transport-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polygon points="16.24,7.76 14.12,14.12 7.76,16.24 9.88,9.88 16.24,7.76"/>
              </svg>
            </div>
            <h4>Diaľnica D1</h4>
            <p>Nájazd do 5 min</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
