<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-bg placeholder-image"></div>
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <span class="hero-label">› BÝVAJTE S RADOSŤOU</span>
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
              <strong>Lorem ipsum dolor</strong> sit amet consectetur. Vitae est non nisl auctor imperdiet etiam tempus facilisi. Phasere duis ut arcu ut aliquet maecenas magna quisque. Lorem ipsum dolor sit amet consectetur...
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
            <div class="about-image-wrapper placeholder-image"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Apartment Types Section -->
    <section class="apartment-types section">
      <div class="container">
        <!-- 2 izbové byty - image left, content right -->
        <div class="apartment-type-row">
          <div class="apartment-type-image">
            <div class="apartment-image-wrapper placeholder-image"></div>
          </div>
          <div class="apartment-type-content">
            <span class="apartment-type-label">Typ bytu</span>
            <h2 class="apartment-type-title">2 izbové byty</h2>
            <p class="apartment-type-text">
              Lorem ipsum dolor sit amet consectetur. Vitae est non nisl auctor imperdiet etiam tempus facilisi. Posuere duis ut arcu ut aliquet maecenas magna quisque. Lorem ipsum dolor sit amet consectetur...
            </p>
            <div class="apartment-type-badges">
              <div class="apartment-badge">
                <img src="~/assets/icons/ui/expand.svg" alt="Rozloha" width="16" height="16" />
                <span>od 40 m²</span>
              </div>
              <div class="apartment-badge">
                <img src="~/assets/icons/ui/layout.svg" alt="Izby" width="16" height="16" />
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
              Lorem ipsum dolor sit amet consectetur. Vitae est non nisl auctor imperdiet etiam tempus facilisi. Posuere duis ut arcu ut aliquet maecenas magna quisque. Lorem ipsum dolor sit amet consectetur...
            </p>
            <div class="apartment-type-badges">
              <div class="apartment-badge">
                <img src="~/assets/icons/ui/expand.svg" alt="Rozloha" width="16" height="16" />
                <span>od 70 m²</span>
              </div>
              <div class="apartment-badge">
                <img src="~/assets/icons/ui/layout.svg" alt="Izby" width="16" height="16" />
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
            <div class="apartment-image-wrapper placeholder-image"></div>
          </div>
        </div>

        <!-- Parkovacie státia -->
        <div class="parking-section">
          <div class="parking-content">
            <h2 class="parking-title"><span class="text-gold">Parkovacie státia</span> na predaj</h2>
            <p class="parking-text">
              Lorem ipsum dolor sit amet consectetur. Vitae est non nisl auctor imperdiet etiam tempus facilisi. Posuere duis ut arcu ut aliquet maecenas magna quisque. Lorem ipsum dolor sit amet consectetur...
            </p>
            <NuxtLink to="/parking" class="parking-link">
              Prečítajte si viac →
            </NuxtLink>
          </div>
          <div class="parking-visual">
            <div class="parking-image-wrapper">
              <!-- Placeholder for parking image -->
            </div>
            <div class="parking-badges">
              <div class="parking-badge">
                <span class="parking-badge-icon">P</span>
                <span>24 parkovacích miest</span>
              </div>
              <div class="parking-badge">
                <img src="~/assets/icons/ui/shield-check.svg" alt="Bezpečnosť" width="20" height="20" />
                <span>Bezpečnosť vozidla</span>
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
              <!-- Google Maps placeholder -->
            </div>
          </div>
          <div class="location-content">
            <span class="location-label">Služby</span>
            <h2 class="location-title">Dostupnosť služieb v okolí bytového projektu</h2>
            <p class="location-text">
              Lorem ipsum dolor sit amet consectetur. Vitae est non nisl auctor imperdiet etiam tempus facilisi. Posuere duis ut arcu ut aliquet maecenas magna quisque. Lorem ipsum dolor sit amet consectetur. Vitae est non nisl auctor imperdiet...
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
    <section class="amenities section">
      <div class="container">
        <div class="amenities-grid">
          <!-- Row 1 -->
          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--teal">
              <img src="~/assets/icons/amenities/waves.svg" alt="Vodné dielo" width="20" height="20" />
            </div>
            <span class="amenity-label">Vodné dielo, šport</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--green">
              <img src="~/assets/icons/amenities/university.svg" alt="Školy" width="20" height="20" />
            </div>
            <span class="amenity-label">Školy</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--orange">
              <img src="~/assets/icons/amenities/mail.svg" alt="Pošta" width="20" height="20" />
            </div>
            <span class="amenity-label">Pošta</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--brown">
              <img src="~/assets/icons/amenities/church.svg" alt="Kostol" width="20" height="20" />
            </div>
            <span class="amenity-label">Kostol</span>
          </div>

          <!-- Row 2 -->
          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--red">
              <img src="~/assets/icons/amenities/baby.svg" alt="Jasle" width="20" height="20" />
            </div>
            <span class="amenity-label">Jasle a škôlky</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--lime">
              <img src="~/assets/icons/amenities/tree-deciduous-white.svg" alt="Parky" width="20" height="20" />
            </div>
            <span class="amenity-label">Parky</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--emerald">
              <img src="~/assets/icons/amenities/activity.svg" alt="Lekárne" width="20" height="20" />
            </div>
            <span class="amenity-label">Lekárne</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--blue">
              <img src="~/assets/icons/amenities/phone.svg" alt="Klientske centrum" width="20" height="20" />
            </div>
            <span class="amenity-label">Klientske centrum</span>
          </div>

          <!-- Row 3 -->
          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--cyan">
              <img src="~/assets/icons/amenities/coffee.svg" alt="Kaviarne" width="20" height="20" />
            </div>
            <span class="amenity-label">Kaviarne</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--purple">
              <img src="~/assets/icons/amenities/utensils.svg" alt="Reštaurácie" width="20" height="20" />
            </div>
            <span class="amenity-label">Reštaurácie</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--gold">
              <img src="~/assets/icons/amenities/shopping-bag.svg" alt="Obchody" width="20" height="20" />
            </div>
            <span class="amenity-label">Obchody</span>
          </div>

          <div class="amenity-item">
            <div class="amenity-icon amenity-icon--salmon">
              <img src="~/assets/icons/amenities/fuel.svg" alt="Čerpacie stanice" width="20" height="20" />
            </div>
            <span class="amenity-label">Čerpacie stanice</span>
          </div>
        </div>

        <!-- Badge at bottom -->
        <div class="amenities-badge-wrapper">
          <div class="amenities-badge">
            <img src="~/assets/icons/ui/map-pin.svg" alt="Služby v okolí" width="20" height="20" />
            <span>Služby v okolí 1,5km, 15 min pešo</span>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA + Salesperson Section -->
    <HomeCtaSection />
  </div>
</template>
