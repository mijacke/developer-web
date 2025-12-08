<template>
  <div class="about-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>O projekte</span>
      </nav>
    </div>

    <!-- Hero Section -->
    <section class="page-hero">
      <div class="page-hero-bg placeholder-image"></div>
      <div class="page-hero-overlay"></div>
      <div class="page-hero-content">
        <h1 class="page-title">O projekte</h1>
        <p class="page-subtitle">Rezidencia Žilina - moderné bývanie v harmónii s prírodou</p>
      </div>
    </section>

    <!-- About Content -->
    <section class="about-content section">
      <div class="container">
        <div class="about-grid">
          <div class="about-text">
            <h2>Kvalitné bývanie pre modernú rodinu</h2>
            <p>
              Rezidencia Žilina predstavuje jedinečný bytový projekt, ktorý spája 
              moderný dizajn s príjemným prostredím. Nachádza sa v atraktívnej lokalite 
              s vynikajúcou dostupnosťou do centra mesta a okolitých obcí.
            </p>
            <p>
              Projekt ponúka široký výber dispozícií od útulných jednoizbových bytov 
              až po priestranné štvorizbové apartmány. Každý byt je navrhnutý s dôrazom 
              na funkčnosť, svetlosť a komfort bývania.
            </p>
          </div>
          <div class="about-image placeholder-image"></div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="about-features section bg-light">
      <div class="container">
        <h2 class="section-title text-center">Čo ponúkame</h2>
        
        <div class="features-list">
          <div class="feature-item">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
            </div>
            <div class="feature-info">
              <h4>Moderné dispozície</h4>
              <p>1 až 4-izbové byty prispôsobené potrebám dnešného života</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
            </div>
            <div class="feature-info">
              <h4>Kvalitné materiály</h4>
              <p>Používame len overené a certifikované stavebné materiály</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
            </div>
            <div class="feature-info">
              <h4>Energetická úspornosť</h4>
              <p>Budova spĺňa najprísnejšie normy energetickej hospodárnosti</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
            </div>
            <div class="feature-info">
              <h4>Parkovacie miesta</h4>
              <p>Zabezpečené podzemné parkovanie pre každý byt</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
            </div>
            <div class="feature-info">
              <h4>Balkóny a terasy</h4>
              <p>Priestranné vonkajšie priestory k väčšine bytov</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
            </div>
            <div class="feature-info">
              <h4>Zelené okolie</h4>
              <p>Projekt je obklopený zeleňou a parkovou úpravou</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="about-stats section">
      <div class="container">
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-number">48</span>
            <span class="stat-label">Bytov celkom</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">4</span>
            <span class="stat-label">Podlaží</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">56</span>
            <span class="stat-label">Parkovacích miest</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">2024</span>
            <span class="stat-label">Rok dokončenia</span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
