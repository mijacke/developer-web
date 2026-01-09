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
    <section class="cta-salesperson">
      <div class="container">
        <div class="cta-salesperson-grid">
          <!-- Left: CTA -->
          <div class="cta-content">
            <h2 class="cta-title">Máte záujem o nový domov?</h2>
            <p class="cta-text">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.
            </p>
            <div class="cta-buttons">
              <NuxtLink to="/apartment-selection" class="btn btn-outline btn-rounded btn-dark">
                Otvoriť ponuku bytov
              </NuxtLink>
              <NuxtLink to="/contact" class="btn btn-text btn-dark">
                Kontaktujte nás
              </NuxtLink>
            </div>
          </div>

          <!-- Right: Salesperson -->
          <div class="salesperson-content">
            <h3 class="salesperson-heading">Kontakt na predajcu</h3>
            <div class="salesperson-card">
              <div class="salesperson-photo placeholder-image"></div>
              <div class="salesperson-info">
                <h4 class="salesperson-name">Meno Priezvisko</h4>
                <span class="salesperson-role">Predajca</span>
                <div class="salesperson-contact">
                  <a href="tel:+421900000000" class="contact-item">
                    <img src="~/assets/icons/contact/phone-gold.svg" alt="Telefón" width="18" height="18" />
                    +421 900 000 000
                  </a>
                  <a href="mailto:info@rezidenciazilina.sk" class="contact-item">
                    <img src="~/assets/icons/contact/mail-gold.svg" alt="Email" width="18" height="18" />
                    info@rezidenciazilina.sk
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Decorative Tree Image -->
      <div class="tree-decoration">
        <div class="tree-image placeholder-image"></div>
      </div>
    </section>
  </div>
</template>
