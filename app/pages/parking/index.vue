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
