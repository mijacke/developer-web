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

    <!-- Hero Section -->
    <section class="page-hero">
      <div class="page-hero-bg placeholder-image"></div>
      <div class="page-hero-overlay"></div>
      <div class="page-hero-content">
        <h1 class="page-title">Parkovanie</h1>
        <p class="page-subtitle">Komfortné parkovacie riešenia pre našich rezidentov</p>
      </div>
    </section>

    <!-- Parking Content -->
    <section class="parking-content section">
      <div class="container">
        <div class="parking-grid">
          <div class="parking-text">
            <h2>Podzemné parkovanie</h2>
            <p>
              Rezidencia Žilina ponúka zabezpečené podzemné parkovanie 
              s dostatočnou kapacitou pre všetkých rezidentov. Každý byt má 
              možnosť zakúpiť si vlastné parkovacie miesto v suterénnych 
              priestoroch budovy.
            </p>
            <p>
              Parkovisko je vybavené moderným vstupným systémom s diaľkovým 
              ovládaním a 24-hodinovým kamerovým dohľadom pre maximálnu 
              bezpečnosť vášho vozidla.
            </p>
          </div>
          <div class="parking-image placeholder-image"></div>
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
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              </svg>
            </div>
            <h4>Zabezpečené</h4>
            <p>24/7 kamerový systém a kontrolovaný prístup</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12,6 12,12 16,14"/>
              </svg>
            </div>
            <h4>Dostupné 24/7</h4>
            <p>Nepretržitý prístup k vášmu vozidlu</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
              </svg>
            </div>
            <h4>Kryté</h4>
            <p>Ochrana pred poveternostnými vplyvmi</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/>
              </svg>
            </div>
            <h4>E-nabíjanie</h4>
            <p>Príprava na nabíjanie elektromobilov</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section class="parking-pricing section">
      <div class="container">
        <h2 class="section-title text-center">Cenník parkovacích miest</h2>
        <p class="section-subtitle text-center">
          Parkovacie miesto je možné zakúpiť spolu s bytom
        </p>
        
        <div class="pricing-grid">
          <div class="pricing-card">
            <div class="pricing-header">
              <h3>Štandardné miesto</h3>
              <div class="price">od 12 000 €</div>
            </div>
            <ul class="pricing-features">
              <li>Rozmer 2,5 x 5 m</li>
              <li>Zabezpečený prístup</li>
              <li>Kamerový dohľad</li>
              <li>Vlastníctvo miesta</li>
            </ul>
          </div>

          <div class="pricing-card featured">
            <div class="pricing-badge">Odporúčané</div>
            <div class="pricing-header">
              <h3>Dvojmiesto</h3>
              <div class="price">od 22 000 €</div>
            </div>
            <ul class="pricing-features">
              <li>Rozmer 5 x 5 m</li>
              <li>Pre 2 vozidlá</li>
              <li>Zabezpečený prístup</li>
              <li>Kamerový dohľad</li>
              <li>Vlastníctvo miesta</li>
            </ul>
          </div>

          <div class="pricing-card">
            <div class="pricing-header">
              <h3>E-miesto</h3>
              <div class="price">od 15 000 €</div>
            </div>
            <ul class="pricing-features">
              <li>Rozmer 2,5 x 5 m</li>
              <li>Príprava pre nabíjačku</li>
              <li>Zabezpečený prístup</li>
              <li>Kamerový dohľad</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
