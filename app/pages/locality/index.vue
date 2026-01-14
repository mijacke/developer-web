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

    <!-- Attractions Section -->
    <section class="attractions-section section">
      <div class="container">
        <!-- Attraction 1: Text Left, Image Right -->
        <div class="attraction-row">
          <div class="attraction-content">
            <span class="attraction-label">V okolí nájdete</span>
            <h2 class="attraction-title">Liptovská mara</h2>
            <p class="attraction-text">
              Liptovská Mara je vodné dielo medzi Liptovským Mikulášom a Ružomberkom, 
              ktoré sa nazýva aj „Liptovské more". Je súčasťou systému priehrad Vážskej 
              kaskády. Celkovým objemom 360,5 mil. m3 je najväčšou vodnou nádržou na 
              Slovensku. Hlavným účelom priehrady je protipovodňová ochrana, ale slúži aj 
              na výrobu elektrickej energie..
            </p>
            <a href="#" class="attraction-link">Čítať viac</a>
          </div>
          <div class="attraction-image">
            <div class="attraction-image-wrapper placeholder-image"></div>
          </div>
        </div>

        <!-- Attraction 2: Image Left, Text Right -->
        <div class="attraction-row attraction-row--reversed">
          <div class="attraction-image">
            <div class="attraction-image-wrapper placeholder-image"></div>
          </div>
          <div class="attraction-content">
            <span class="attraction-label">V okolí nájdete</span>
            <h2 class="attraction-title">Tatralandia</h2>
            <p class="attraction-text">
              Ponorte sa do termálnych aj slaných bazénov v prostredí s tropickou klímou, 
              kde panuje príjemných 30°C, a zabudnite na chladné dni. Zažite kúpanie pod 
              hviezdami, osviežujúce saunové rituály a špeciálne eventy plné energie. Deti 
              sa zabavia na toboganoch a šmýkačkách, zatiaľ čo rodičia si oddýchnu v 
              saunovom svete alebo doprajú telu relaxačné masáže a wellness procedúry. 
              Tropická Tatralandia spája zábavu, relax a nezabudnuteľné zážitky pre 
              všetkých
            </p>
            <a href="#" class="attraction-link">Čítať viac</a>
          </div>
        </div>

        <!-- Attraction 3: Text Left, Image Right -->
        <div class="attraction-row">
          <div class="attraction-content">
            <span class="attraction-label">V okolí nájdete</span>
            <h2 class="attraction-title">Lyžiarske stredisko Jasná</h2>
            <p class="attraction-text">
              Jasná – Chopok je najväčšie lyžiarske stredisko na Slovensku s výbornými 
              podmienkami pre zimné športy a ponúka nekonečné možnosti aktivít, zábavy a 
              aktívneho oddychu. Ponúka 50 km perfektne upravených zjazdoviek rôznych 
              obtiažností, vhodných pre začiatočníkov, rodiny s deťmi, pokročilých lyžiarov a 
              aj na usporiadanie pretekov svetovej úrovne.
            </p>
            <a href="#" class="attraction-link">Čítať viac</a>
          </div>
          <div class="attraction-image">
            <div class="attraction-image-wrapper placeholder-image"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery Section -->
    <section class="locality-gallery-section section">
      <div class="container">
        <div class="gallery-header text-center">
          <h2 class="gallery-title">Liptov, ako to vyzerá v okolí</h2>
          <p class="gallery-description">
            Lorem ipsum dolor sit amet consectetur. Vitae est non nisl auctor imperdiet etiam tempus facilisi.
          </p>
        </div>
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
    </section>

    <!-- CTA Section -->
    <HomeCtaSection />
  </div>

</template>
