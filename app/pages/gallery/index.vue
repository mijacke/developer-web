<template>
  <div class="gallery-page">
    <!-- Breadcrumbs -->
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>Galéria</span>
      </nav>
    </div>

    <!-- Gallery Content -->
    <section class="gallery-content section">
      <div class="container">
        <!-- Exterior Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Exteriér</h2>
          <p class="gallery-section-desc">
            Vonkajšie priestory rezidencie s príjemnou parkovou úpravou
          </p>
          
          <div class="gallery-grid">
            <div class="gallery-item large">
              <NuxtImg
                src="/images/gallery/exterior/gallery-exterior-1.png"
                alt="Moderný exteriér Rezidencia Žilina"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/exterior/gallery-exterior-2.png"
                alt="Parková úprava a vnútroblok"
                width="400"
                height="300"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item tall">
              <NuxtImg
                src="/images/gallery/exterior/gallery-exterior-3.png"
                alt="Detské ihrisko v areáli"
                width="400"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/exterior/gallery-exterior-4.png"
                alt="Detail fasády a architektúry"
                width="400"
                height="300"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/exterior/gallery-exterior-5.png"
                alt="Večerná atmosféra rezidencie"
                width="400"
                height="300"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item large">
              <NuxtImg
                src="/images/gallery/exterior/gallery-exterior-6.png"
                alt="Vstup do rezidencie a lobby"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
          </div>
        </div>

        <!-- Interior Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Interiér</h2>
          <p class="gallery-section-desc">
            Vzorové byty s modernými interiérmi
          </p>
          
          <div class="gallery-grid">
            <div class="gallery-item large">
              <NuxtImg
                src="/images/gallery/interior/gallery-interior-1.png"
                alt="Moderná obývačka s výhľadom"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/interior/gallery-interior-2.png"
                alt="Dizajnová kuchyňa"
                width="400"
                height="300"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item tall">
              <NuxtImg
                src="/images/gallery/interior/gallery-interior-3.png"
                alt="Hlavná spálňa"
                width="400"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/interior/gallery-interior-4.png"
                alt="Luxusná kúpeľňa"
                width="400"
                height="300"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/interior/gallery-interior-5.png"
                alt="Vstupná chodba"
                width="400"
                height="300"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item large">
              <NuxtImg
                src="/images/gallery/interior/gallery-interior-6.png"
                alt="Pracovňa (home office)"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
          </div>
        </div>

        <!-- Construction Section -->
        <div class="gallery-section">
          <h2 class="gallery-section-title">Výstavba</h2>
          <p class="gallery-section-desc">
            Sledujte priebeh výstavby rezidencie
          </p>
          
          <div class="gallery-grid gallery-grid--construction">
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/construction/gallery-construction-1.png"
                alt="Príprava staveniska a výkopové práce"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/construction/gallery-construction-2.png"
                alt="Realizácia základov"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/construction/gallery-construction-3.png"
                alt="Hrubá stavba a betonáž"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/construction/gallery-construction-4.png"
                alt="Murovanie obvodových stien"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/construction/gallery-construction-5.png"
                alt="Realizácia fasády a okien"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
            <div class="gallery-item">
              <NuxtImg
                src="/images/gallery/construction/gallery-construction-6.png"
                alt="Dokončená moderná rezidencia"
                width="800"
                height="600"
                loading="lazy"
                class="gallery-image"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <HomeCtaSection />
  </div>
</template>
