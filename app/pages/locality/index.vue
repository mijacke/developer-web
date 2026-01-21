

<template>
  <div class="locality-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>Lokalita</span>
      </nav>
    </div>

    <!-- Hero Section - New Style -->
    <section class="locality-hero section">
      <div class="container">


        <!-- Hero Content -->
        <div class="locality-hero-content">
          <h1 class="locality-title">Lokalita</h1>
          <p class="locality-subtitle">
            <strong>Objavte moderné bývanie v srdci prírody.</strong> Rezidencia Žilina spája komfort mestského života s pokojom zelene.
          </p>
        </div>

        <!-- Panoramic Image -->
        <div class="locality-panorama">
          <NuxtImg
            src="/images/locality/panoramatic.png"
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
            title="Rezidencia Žilina – mapa"
            src="https://www.openstreetmap.org/export/embed.html?bbox=18.7378%2C49.1889%2C18.7778%2C49.2089&layer=mapnik&marker=49.1989%2C18.7578"
            width="100%"
            height="100%"
            style="border:0;"
            loading="lazy"
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

          </div>
          <div class="attraction-image">
            <div class="attraction-image-wrapper">
              <NuxtImg
                src="/images/locality/budatinsky-hrad.png"
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
                src="/images/locality/vodne-dielo-zilina.png"
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

          </div>
          <div class="attraction-image">
            <div class="attraction-image-wrapper">
              <NuxtImg
                src="/images/locality/hrad-strecno.png"
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
          <h2 class="gallery-title">Žilina a okolie objektívom</h2>
          <p class="gallery-description">
            Spoznajte jedinečnú atmosféru mesta a jeho prírodného bohatstva. Od historického centra s neopakovateľným čarom až po zelené oázy pokoja a majestátne hory na dosah ruky.
          </p>
        </div>
        <div class="gallery-grid">
          <div class="gallery-item">
            <NuxtImg
              src="/images/locality/marianske-namestie.png"
              alt="Mariánske námestie"
              width="400"
              height="300"
              loading="lazy"
              class="gallery-image"
            />
          </div>
          <div class="gallery-item">
            <NuxtImg
              src="/images/locality/mala-fatra.png"
              alt="Výhľad na Malú Fatru"
              width="400"
              height="300"
              loading="lazy"
              class="gallery-image"
            />
          </div>
          <div class="gallery-item">
            <NuxtImg
              src="/images/locality/lesopark-chrast.png"
              alt="Lesopark Chrasť"
              width="400"
              height="300"
              loading="lazy"
              class="gallery-image"
            />
          </div>
          <div class="gallery-item">
            <NuxtImg
              src="/images/locality/zilina-panorama.png"
              alt="Nočná panoráma Žiliny"
              width="400"
              height="300"
              loading="lazy"
              class="gallery-image"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <HomeCtaSection />
  </div>

</template>

<script setup lang="ts">
// SEO Meta Tags
useSeoMeta({
  title: 'Lokalita | Rezidencia Žilina',
  description: 'Objavte lokalitu Rezidencia Žilina - blízkosť Budatínskeho hradu, Vodného diela Žilina a Hradu Strečno. Perfektná kombinácia prírody a mestského života.',
  ogTitle: 'Lokalita | Rezidencia Žilina',
  ogDescription: 'Atraktívna lokalita s výbornou dostupnosťou centra mesta a prírodných krás Žiliny.',
  ogImage: '/images/locality/panoramatic.png',
  ogType: 'website'
})
</script>
