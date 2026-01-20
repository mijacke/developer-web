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
          <NuxtImg
            src="/images/panoramatic.png"
            alt="Panoráma okolia Rezidencia Žilina"
            loading="eager"
            class="panorama-image"
          />
        </div>
      </div>
    </section>

    <!-- Amenities Grid Section -->
    <SharedAmenitiesGrid />

    <!-- Google Maps Section -->
    <section class="locality-map-section">
      <div class="container">
        <div class="locality-map-wrapper">
          <iframe
            src="https://maps.google.com/maps?q=Lesopark+Chras%C5%A5,+%C5%BDilina&t=&z=15&ie=UTF8&iwloc=&output=embed"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>
    </section>

    <!-- Attractions Section -->
    <section class="attractions-section section">
      <div class="container">
        <!-- Attraction 1: Text Left, Image Right -->
        <div class="attraction-row">
          <div class="attraction-content">
            <span class="attraction-label">V okolí nájdete</span>
            <h2 class="attraction-title">Budatínsky hrad</h2>
            <p class="attraction-text">
              Historická dominanta Žiliny, ktorá sa nachádza na sútoku riek Váh a Kysuca. Hrad je obklopený rozsiahlym anglickým parkom, ktorý je ideálny na prechádzky a pikniky. V interiéri hradu nájdete Považské múzeum s unikátnou expozíciou drotárstva.
            </p>
            <a href="#" class="attraction-link">Čítať viac</a>
          </div>
          <div class="attraction-image">
            <div class="attraction-image-wrapper">
              <NuxtImg
                src="/images/budatinsky-hrad.png"
                alt="Budatínsky hrad"
                width="600"
                height="340"
                loading="lazy"
                class="full-image"
              />
            </div>
          </div>
        </div>

        <!-- Attraction 2: Image Left, Text Right -->
        <div class="attraction-row attraction-row--reversed">
          <div class="attraction-image">
            <div class="attraction-image-wrapper">
              <NuxtImg
                src="/images/vodne-dielo-zilina.png"
                alt="Vodné dielo Žilina"
                width="600"
                height="340"
                loading="lazy"
                class="full-image"
              />
            </div>
          </div>
          <div class="attraction-content">
            <span class="attraction-label">V okolí nájdete</span>
            <h2 class="attraction-title">Vodné dielo Žilina</h2>
            <p class="attraction-text">
              Obľúbené miesto pre šport a rekreáciu. Okolie vodného diela ponúka perfektné podmienky pre cyklistov, korčuliarov aj bežcov s krásnymi výhľadmi na okolitú prírodu a Malú Fatru. V letných mesiacoch tu nájdete aj možnosti na vodné športy.
            </p>
            <a href="#" class="attraction-link">Čítať viac</a>
          </div>
        </div>

        <!-- Attraction 3: Text Left, Image Right -->
        <div class="attraction-row">
          <div class="attraction-content">
            <span class="attraction-label">V okolí nájdete</span>
            <h2 class="attraction-title">Hrad Strečno</h2>
            <p class="attraction-text">
              Majestátna zrúcanina stredovekého hradu týčiaca sa na vápencovom brale nad riekou Váh. Hrad ponúka nielen fascinujúci pohľad do histórie, ale aj nádherné panoramatické výhľady na Domašínsky meander a okolitú krajinu.
            </p>
            <a href="#" class="attraction-link">Čítať viac</a>
          </div>
          <div class="attraction-image">
            <div class="attraction-image-wrapper">
              <NuxtImg
                src="/images/hrad-strecno.png"
                alt="Hrad Strečno"
                width="600"
                height="340"
                loading="lazy"
                class="full-image"
              />
            </div>
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
