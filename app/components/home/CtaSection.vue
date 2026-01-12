<template>
  <section class="cta-salesperson">
    <div class="container">
      <div class="cta-salesperson-grid">
        <!-- Left: CTA Content -->
        <div class="cta-content">
          <h2 class="cta-title">Máte záujem o nový domov?</h2>
          <p class="cta-text">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.
          </p>
          <div class="cta-buttons">
            <NuxtLink to="/apartment-selection" class="btn btn-cta-primary btn-rounded">
              Otvoriť ponuku bytov
            </NuxtLink>
            <NuxtLink to="/contact" class="btn btn-cta-secondary btn-rounded">
              Kontaktujte nás
            </NuxtLink>
          </div>
        </div>

        <!-- Right: Salesperson Card -->
        <HomeSalespersonCard
          name="Meno Priezvisko"
          role="Predajca"
          phone="+421 900 000 000"
          email="info@rezidenciazilina.sk"
        />
      </div>
    </div>


    <div class="tree-decoration">
      <HomeLottieTree />
    </div>
    
  </section>
</template>

<script setup lang="ts">
</script>
