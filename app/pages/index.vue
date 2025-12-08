<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-bg placeholder-image"></div>
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1 class="hero-title">Privítajte novú kapitolu života</h1>
        <p class="hero-subtitle">Moderné bývanie v projekte Rezidencia Žilina</p>
        <div class="hero-buttons">
          <NuxtLink to="/apartment-selection" class="btn btn-primary">
            Vybrať byt
          </NuxtLink>
          <NuxtLink to="/about-project" class="btn btn-outline">
            O projekte
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features section">
      <div class="container">
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-image placeholder-image"></div>
            <div class="feature-content">
              <h3 class="feature-title">Krásne nové byty</h3>
              <p class="feature-text">
                Moderné dispozície prispôsobené potrebám dnešného života. 
                Kvalitné materiály a precízne spracovanie.
              </p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-image placeholder-image"></div>
            <div class="feature-content">
              <h3 class="feature-title">Príjemné prostredie</h3>
              <p class="feature-text">
                Lokalita v blízkosti zelene a s vynikajúcou dostupnosťou 
                do centra Žiliny aj okolitých obcí.
              </p>
            </div>
          </div>

          <div class="feature-card">
            <div class="feature-image placeholder-image"></div>
            <div class="feature-content">
              <h3 class="feature-title">Investícia ktorá sa oplatí</h3>
              <p class="feature-text">
                Stabilná hodnota nehnuteľnosti v rastúcej lokalite 
                s potenciálom ďalšieho zhodnotenia.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="services section bg-light">
      <div class="container">
        <h2 class="section-title text-center">Služby v okolí</h2>
        <p class="section-subtitle text-center">
          Všetko, čo potrebujete, na dosah ruky
        </p>

        <div class="services-grid">
          <div class="service-item">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9,22 9,12 15,12 15,22"/>
              </svg>
            </div>
            <span class="service-label">Školy</span>
          </div>

          <div class="service-item">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                <line x1="9" y1="9" x2="9.01" y2="9"/>
                <line x1="15" y1="9" x2="15.01" y2="9"/>
              </svg>
            </div>
            <span class="service-label">Škôlky</span>
          </div>

          <div class="service-item">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
              </svg>
            </div>
            <span class="service-label">Obchody</span>
          </div>

          <div class="service-item">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
              </svg>
            </div>
            <span class="service-label">Zdravotníctvo</span>
          </div>

          <div class="service-item">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="3" width="15" height="13"/>
                <polygon points="16,8 20,8 23,11 23,16 16,16 16,8"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
            <span class="service-label">Doprava</span>
          </div>

          <div class="service-item">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                <path d="M16 3.13a4 4 0 010 7.75"/>
              </svg>
            </div>
            <span class="service-label">Reštaurácie</span>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta section bg-dark">
      <div class="container">
        <div class="cta-content">
          <h2 class="cta-title text-white">Máte záujem o nový domov?</h2>
          <p class="cta-text text-white">
            Kontaktujte nás a dohodnite si nezáväznú prehliadku bytov
          </p>
          <div class="cta-buttons">
            <NuxtLink to="/contact" class="btn btn-primary">
              Kontaktujte nás
            </NuxtLink>
            <NuxtLink to="/apartment-selection" class="btn btn-outline">
              Vybrať byt
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
