<script setup lang="ts">
definePageMeta({
  layout: 'default'
})
</script>

<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero">
      <NuxtImg
        src="/images/home/hero.png"
        alt="Rezidencia Žilina"
        class="hero-bg"
        loading="eager"
      />
      <div class="hero-overlay"></div>
      <div class="hero-content">

        <h1 class="hero-title">Bytový projekt<br>Rezidencia Žilina</h1>
        <p class="hero-subtitle">Objavte moderné bývanie v srdci prírody. Rezidencia Žilina spája komfort mestského života s pokojom zelene.</p>
        <div class="hero-buttons">
          <NuxtLink to="/contact" class="btn btn-primary btn-rounded">
            Kontakt
          </NuxtLink>
          <NuxtLink to="/apartment-selection" class="btn btn-outline btn-rounded">
            Výber bytu
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Feature Cards Section -->
    <section class="features section">
      <div class="container">
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-header">
              <div class="feature-icon">
                <img src="~/assets/icons/features/house.svg" alt="Byty" width="40" height="40" />
              </div>
              <h3 class="feature-title">Krásne nové byty</h3>
            </div>
            <p class="feature-text">
              Moderné, priestranné a svetlé byty navrhnuté s dôrazom na kvalitu...
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <div class="feature-icon">
                <img src="~/assets/icons/features/tree-deciduous.svg" alt="Prostredie" width="40" height="40" />
              </div>
              <h3 class="feature-title">Príjemné prostredie</h3>
            </div>
            <p class="feature-text">
              Bývajte v lokalite plnej zelene, s parkom a miestami na oddych hneď pri byte.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <div class="feature-icon">
                <img src="~/assets/icons/features/chart-line.svg" alt="Investícia" width="40" height="40" />
              </div>
              <h3 class="feature-title">Investícia ktorá sa oplatí</h3>
            </div>
            <p class="feature-text">
              Hodnota vášho bývania porastie, výborná príležitosť pre budúcich majiteľov.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- About Project Section -->
    <section class="about-project section">
      <div class="container">
        <div class="about-grid">
          <div class="about-content">
            <span class="about-label">O projekte</span>
            <h2 class="about-title">Bytový projekt<br><span class="text-gold">Rezidencia Žilina</span></h2>
            <p class="about-text">
              <strong>Rezidencia Žilina</strong> je moderný bytový komplex situovaný v atraktívnej lokalite s výbornou dostupnosťou do centra mesta. Projekt ponúka kvalitné bývanie v 2 a 3-izbových bytoch s nadštandardným vybavením, priestrannými balkónmi a podzemným parkovaním.
            </p>
            <div class="about-buttons">
              <NuxtLink to="/about-project" class="btn btn-primary btn-rounded">
                O projekte
              </NuxtLink>
              <NuxtLink to="/locality" class="btn btn-outline-dark btn-rounded">
                Lokalita
              </NuxtLink>
            </div>
          </div>
          <div class="about-image">
            <NuxtImg
              src="/images/home/about-project.jpg"
              alt="Rezidencia Žilina - architektonický návrh"
              class="about-image-wrapper"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Apartment Types Section -->
    <section class="apartment-types section">
      <div class="container">
        <!-- Header s názvom a štatistikami -->
        <div class="apartment-header">
          <div class="apartment-header-content">
            <h2 class="apartment-header-title">Aké byty u nás nájdete?</h2>
            <p class="apartment-header-text">
              V Rezidencii Žilina ponúkame moderné 2 a 3-izbové byty s premysleným dispozičným riešením. Každý byt disponuje priestranným balkónom a možnosťou zakúpenia parkovacieho miesta v podzemnej garáži.
            </p>
          </div>
          <div class="apartment-stats">
            <div class="stat-box">
              <span class="stat-label">Parkovacie miesta</span>
              <span class="stat-value">5/24</span>
            </div>
            <div class="stat-box">
              <span class="stat-label">Obsadenosť bytov</span>
              <span class="stat-value">8/17</span>
            </div>
          </div>
        </div>

        <!-- 2 izbové byty - image left, content right -->
        <div class="apartment-type-row">
          <div class="apartment-type-image">
            <NuxtImg
              src="/images/home/apartment-2room.png"
              alt="2-izbový byt Rezidencia Žilina"
              class="apartment-image-wrapper"
              loading="lazy"
              width="600"
              height="400"
            />
          </div>
          <div class="apartment-type-content">
            <span class="apartment-type-label">Typ bytu</span>
            <h2 class="apartment-type-title">2 izbové byty</h2>
            <p class="apartment-type-text">
              Ideálne riešenie pre jednotlivcov alebo páry. Svetlé a priestranné byty s otvorenou kuchyňou spojenou s obývacou izbou, samostatnou spálňou a modernou kúpeľňou. Veľké francúzske okná poskytujú dostatok denného svetla.
            </p>
            <div class="apartment-type-badges">
              <div class="apartment-badge">
                <img src="/assets/icons/ui/expand.svg" alt="Rozloha" width="16" height="16" />
                <span>od 70 m²</span>
              </div>
              <div class="apartment-badge">
                <img src="/assets/icons/ui/layout.svg" alt="Izby" width="16" height="16" />
                <span>2 izby</span>
              </div>
            </div>
            <div class="apartment-type-buttons">
              <NuxtLink to="/apartment-selection" class="btn btn-primary btn-rounded">
                Otvoriť ponuku bytov
              </NuxtLink>
              <NuxtLink to="/contact" class="btn btn-outline-dark btn-rounded">
                Potrebujem poradiť
              </NuxtLink>
            </div>
          </div>
        </div>

        <!-- 3 izbové byty - content left, image right -->
        <div class="apartment-type-row apartment-type-row--reversed">
          <div class="apartment-type-content">
            <span class="apartment-type-label">Typ bytu</span>
            <h2 class="apartment-type-title">3 izbové byty</h2>
            <p class="apartment-type-text">
              Priestranné rodinné bývanie s dvoma samostatnými spálňami, priestrannou obývacou izbou a plne vybavenou kuchyňou. Veľký balkón ponúka možnosť relaxu s výhľadom na zeleň. Ideálne pre rodiny s deťmi.
            </p>
            <div class="apartment-type-badges">
              <div class="apartment-badge">
                <img src="/assets/icons/ui/expand.svg" alt="Rozloha" width="16" height="16" />
                <span>od 75 m²</span>
              </div>
              <div class="apartment-badge">
                <img src="/assets/icons/ui/layout.svg" alt="Izby" width="16" height="16" />
                <span>3 izby</span>
              </div>
            </div>
            <div class="apartment-type-buttons">
              <NuxtLink to="/apartment-selection" class="btn btn-primary btn-rounded">
                Otvoriť ponuku bytov
              </NuxtLink>
              <NuxtLink to="/contact" class="btn btn-outline-dark btn-rounded">
                Potrebujem poradiť
              </NuxtLink>
            </div>
          </div>
          <div class="apartment-type-image">
            <NuxtImg
              src="/images/home/apartment-3room.png"
              alt="3-izbový byt Rezidencia Žilina"
              class="apartment-image-wrapper"
              loading="lazy"
              width="600"
              height="400"
            />
          </div>
        </div>

        <!-- Parkovacie státia -->
        <div class="parking-section">
          <div class="parking-content">
            <h2 class="parking-title"><span class="text-gold">Parkovacie státia</span> na predaj</h2>
            <p class="parking-text">
              Zabudnite na starosti s hľadaním miesta. Rezidencia Žilina ponúka komfortné a bezpečné parkovanie v podzemnej garáži priamo v objekte. K dispozícii sú parkovacie miesta vyhradené výlučne pre obyvateľov komplexu, čo zaručuje maximálne pohodlie a ochranu vášho vozidla.
            </p>
            <NuxtLink to="/parking" class="parking-link">
              Prečítajte si viac →
            </NuxtLink>
          </div>
          <div class="parking-visual">
            <div class="parking-image-wrapper">
              <img
                src="/images/home/parking-top-view.png"
                alt="Parkovacie státia Rezidencia Žilina"
                class="parking-image"
                loading="lazy"
                decoding="async"
                width="1600"
                height="686"
              />
              <div class="parking-badges">
                <div class="parking-badge">
                  <span class="parking-badge-icon">P</span>
                  <span>Parkovacie miesta</span>
                </div>
                <div class="parking-badge">
                  <img src="~/assets/icons/ui/shield-check.svg" alt="Bezpečnosť" width="20" height="20" />
                  <span>Bezpečnosť vozidla</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Location Section -->
    <section class="location-section section">
      <div class="container">
        <div class="location-grid">
          <div class="location-map">
            <div class="location-map-wrapper">
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
          <div class="location-content">
            <span class="location-label">Služby</span>
            <h2 class="location-title">Dostupnosť služieb v okolí Rezidencia Žilina</h2>
            <p class="location-text">
              Rezidencia Žilina sa nachádza v bezprostrednej blízkosti Lesoparku Chrasť, ktorý ponúka ideálne možnosti na relax a šport. V pešej dostupnosti nájdete kompletnú občiansku vybavenosť vrátane škôl, škôlok, obchodov a zastávok MHD. Centrum mesta je vzdialené len pár minút jazdy.
            </p>
            <div class="location-buttons">
              <NuxtLink to="/locality" class="btn btn-primary btn-rounded">
                Viac o lokalite
              </NuxtLink>
              <NuxtLink to="/parking" class="btn btn-outline-dark btn-rounded">
                Parkovanie
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Amenities Section -->
    <SharedAmenitiesGrid />

    <!-- CTA + Salesperson Section -->
    <HomeCtaSection />
  </div>
</template>
