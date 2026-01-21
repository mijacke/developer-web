<template>
  <div class="about-page">

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
              Nová dominanta bývania v Žiline, ktorá spája mestský životný štýl s pokročilými technológiami a výhľadmi na panorámu Malej Fatry.
            </p>
            <p class="about-intro-text">
              Rezidencia Žilina predstavuje unikátne spojenie nadčasovej architektúry a komfortu. 
              Ponúkame prémiové 2 až 3-izbové apartmány navrhnuté s dôrazom na detail, 
              presvetlenie a funkčnosť. Každý byt disponuje kvalitnými materiálmi, 
              odolnými podlahami z prírodného dubu a modernými smart riešeniami. 
              Či už hľadáte štartovacie bývanie alebo priestranný domov pre rodinu, 
              náš projekt spĺňa najvyššie nároky na súčasné bývanie v srdci Žilinského kraja.
            </p>
            <NuxtLink to="/locality" class="btn btn-primary btn-rounded">
              Viac o lokalite
            </NuxtLink>
          </div>
          <div class="about-intro-image">
            <div class="about-image-wrapper">
              <NuxtImg
                src="/images/about-project/o-lokalite.png"
                alt="O lokalite"
                width="600"
                height="450"
                loading="eager"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Ideal Place Section -->
    <section class="about-ideal section bg-light">
      <div class="container">
        <div class="about-ideal-grid">
          <div class="about-ideal-image">
            <div class="about-image-wrapper">
              <NuxtImg
                src="/images/about-project/contact.png"
                alt="Ideálne miesto pre život"
                width="600"
                height="450"
                loading="lazy"
              />
            </div>
          </div>
          <div class="about-ideal-content">
            <h2 class="about-ideal-title">Ideálne miesto pre život v Žiline</h2>
            <p class="about-ideal-text">
              Lokalita rezidencie bola starostlivo vybraná tak, aby poskytovala perfektnú rovnováhu 
              medzi blízkosťou centra mesta a pokojom domova. V pešej dostupnosti nájdete 
              kompletnú občiansku vybavenosť, školy, parky aj nákupné možnosti. 
              Zároveň si môžete vychutnávať rannú kávu na terase s neopakovateľným výhľadom 
              na okolité kopce, ktoré robia Žilinu tak výnimočnou.
            </p>
            <NuxtLink to="/contact" class="btn btn-primary btn-rounded">
              Kontaktujte nás
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
              <img src="~/assets/icons/what-we-offer/layout.svg" alt="Moderné dispozície" width="32" height="32" />
            </div>
            <h3 class="feature-card-title">Moderné dispozície</h3>
            <p class="feature-card-text">
              Priestranné 2 a 3-izbové byty navrhnuté pre maximálne využitie priestoru a pohodlie.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <img src="~/assets/icons/what-we-offer/materials.svg" alt="Kvalitné materiály" width="32" height="32" />
            </div>
            <h3 class="feature-card-title">Kvalitné materiály</h3>
            <p class="feature-card-text">
              Pri výstavbe používame len prémiové materiály, ktoré zaručujú dlhú životnosť a estetickú hodnotu.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <img src="~/assets/icons/what-we-offer/energy.svg" alt="Energetická úspornosť" width="32" height="32" />
            </div>
            <h3 class="feature-card-title">Energetická úspornosť</h3>
            <p class="feature-card-text">
              Nízkoenergetický štandard A0, ktorý šetrí nielen prírodu, ale aj vašu peňaženku.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <img src="~/assets/icons/what-we-offer/circle-parking.svg" alt="Parkovacie miesta" width="32" height="32" />
            </div>
            <h3 class="feature-card-title">Parkovacie miesta</h3>
            <p class="feature-card-text">
              Dostatok pohodlných parkovacích miest v podzemnej garáži aj na vonkajšom parkovisku.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <img src="~/assets/icons/what-we-offer/sun.svg" alt="Balkóny a terasy" width="32" height="32" />
            </div>
            <h3 class="feature-card-title">Balkóny a terasy</h3>
            <p class="feature-card-text">
              Každý byt disponuje vlastným balkónom alebo terasou s výhľadom na panorámu Žiliny.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-card-icon">
              <img src="~/assets/icons/what-we-offer/shrub.svg" alt="Zelené okolie" width="32" height="32" />
            </div>
            <h3 class="feature-card-title">Zelené okolie</h3>
            <p class="feature-card-text">
              Súčasťou projektu je parková úprava a oddychové zóny priamo v areáli rezidencie.
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
            <span class="stat-number">17</span>
            <span class="stat-label">Bytov celkom</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">2</span>
            <span class="stat-label">Poschodia</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">24</span>
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
