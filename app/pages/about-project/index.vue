<template>
  <div class="about-page">
    <!-- Hero Section -->
    <section class="page-hero page-hero--fullscreen">
      <div class="page-hero-bg placeholder-image"></div>
      <div class="page-hero-overlay"></div>
      <div class="page-hero-content">
        <h1 class="page-title">O projekte</h1>
        <p class="page-subtitle">Rezidencia Žilina - Tam, kde chcete bývať</p>
      </div>
    </section>

    <!-- Breadcrumbs below hero -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>O projekte</span>
      </nav>
    </div>

    <!-- Main About Section -->
    <section class="about-intro section">
      <div class="container">
        <div class="about-intro-grid">
          <div class="about-intro-content">
            <h2 class="about-intro-title">Rezidencia Žilina</h2>
            <p class="about-intro-lead">
              Rezidencia Žilina je moderný developerský projekt situovaný v atraktívnej lokalite 
              s výbornou dostupnosťou do centra mesta a okolitých obcí.
            </p>
            <p class="about-intro-text">
              Vitajte v našom výnimočnom developerskom projekte, ktorý ponúka moderné byty 
              s nadčasovým dizajnom v pokojnej a atraktívnej lokalite. Naši zákazníci majú 
              jedinečnú možnosť vybrať si z rôznych dispozícií od útulných jednoizbových bytov 
              až po priestranné štvorizbové apartmány. Nezáleží na tom, či preferujete pohodu 
              a komfort hotového bývania, alebo si chcete prispôsobiť byt podľa vlastných 
              predstáv – naša ponuka je prispôsobená pre každého.
            </p>
            <NuxtLink to="/locality" class="btn btn-primary btn-rounded">
              Viac o lokalite
            </NuxtLink>
          </div>
          <div class="about-intro-image">
            <div class="about-image-wrapper placeholder-image"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Ideal Place Section -->
    <section class="about-ideal section bg-light">
      <div class="container">
        <div class="about-ideal-grid">
          <div class="about-ideal-image">
            <div class="about-image-wrapper placeholder-image"></div>
          </div>
          <div class="about-ideal-content">
            <h2 class="about-ideal-title">Ideálne miesto pre moderné bývanie</h2>
            <p class="about-ideal-text">
              V srdci Žiliny na vás čaká jedinečný moderný bytový komplex, ktorý spája 
              nadčasový dizajn s pokojom prírody. Doprajte si luxus priestranného bývania 
              v lokalite, kde máte na dosah všetko, čo potrebujete pre aktívny a pohodlný 
              život – od krásnych prírodných scenérií až po kompletnú mestskú infraštruktúru.
            </p>
            <NuxtLink to="/contact" class="btn btn-secondary btn-rounded">
              Kontaktujte nás ešte dnes
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="about-features section">
      <div class="container">
        <h2 class="section-title text-center">Čo ponúkame</h2>
        
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
              </svg>
            </div>
            <h3 class="feature-card-title">Moderné dispozície</h3>
            <p class="feature-card-text">
              1 až 4-izbové byty prispôsobené potrebám dnešného života
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              </svg>
            </div>
            <h3 class="feature-card-title">Kvalitné materiály</h3>
            <p class="feature-card-text">
              Používame len overené a certifikované stavebné materiály
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
              </svg>
            </div>
            <h3 class="feature-card-title">Energetická úspornosť</h3>
            <p class="feature-card-text">
              Budova spĺňa najprísnejšie normy energetickej hospodárnosti
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="3" width="15" height="13"/>
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
            <h3 class="feature-card-title">Parkovacie miesta</h3>
            <p class="feature-card-text">
              Zabezpečené podzemné parkovanie pre každý byt
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <line x1="12" y1="1" x2="12" y2="3"/>
                <line x1="12" y1="21" x2="12" y2="23"/>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                <line x1="1" y1="12" x2="3" y2="12"/>
                <line x1="21" y1="12" x2="23" y2="12"/>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
              </svg>
            </div>
            <h3 class="feature-card-title">Balkóny a terasy</h3>
            <p class="feature-card-text">
              Priestranné vonkajšie priestory k väčšine bytov
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22c-4.97 0-9-2.686-9-6v-4c0-3.314 4.03-6 9-6s9 2.686 9 6v4c0 3.314-4.03 6-9 6z"/>
                <path d="M12 6c-4.03 0-9 2.686-9 6"/>
                <path d="M12 2c-1.657 0-3 1.343-3 3v1"/>
              </svg>
            </div>
            <h3 class="feature-card-title">Zelené okolie</h3>
            <p class="feature-card-text">
              Projekt je obklopený zeleňou a parkovou úpravou
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="about-stats section bg-primary-dark">
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
            <span class="stat-number">2026</span>
            <span class="stat-label">Rok dokončenia</span>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <HomeCtaSection />
  </div>
</template>
