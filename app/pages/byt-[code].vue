<template>
  <div class="apartment-detail-page">
    <div class="container">
      <nav class="breadcrumbs">
        <NuxtLink to="/">Domov</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <NuxtLink to="/apartment-selection">Výber bytu</NuxtLink>
        <span class="breadcrumbs-separator">/</span>
        <span>{{ apartment.floorLabel }}</span>
        <span class="breadcrumbs-separator">/</span>
        <span>Jednotka {{ apartment.code }}</span>
      </nav>
    </div>

    <section class="unit-section section">
      <div class="container">
        <div class="unit-topbar">
          <div class="unit-topbar__spacer" />
          <NuxtLink to="/apartment-selection" class="unit-back">
            <span class="unit-back__icon" aria-hidden="true">←</span>
            <span>Späť na ponuku jednotiek</span>
          </NuxtLink>
        </div>

        <div class="unit-grid">
          <div class="unit-plans">
            <div class="plan-card">
              <NuxtImg
                :src="apartment.floorplanPrimarySrc"
                :alt="`Pôdorys jednotky ${apartment.code}`"
                width="1200"
                height="800"
                loading="eager"
                class="plan-image"
              />
            </div>
            <div class="plan-card">
              <NuxtImg
                :src="apartment.floorplanSecondarySrc"
                :alt="`Pôdorys 2 jednotky ${apartment.code}`"
                width="1200"
                height="800"
                loading="lazy"
                class="plan-image"
              />
            </div>
          </div>

          <aside class="unit-card">
            <header class="unit-card__header">
              <div class="unit-title-row">
                <h1 class="unit-title">Jednotka {{ apartment.code }}</h1>
              </div>

              <div class="unit-tags">
                <span class="unit-tag">{{ apartment.roomsLabel }}</span>
                <span class="unit-tag">{{ apartment.floorLabel }}</span>
              </div>
            </header>

            <div class="unit-area">
              <span class="unit-area__value">{{ areaDisplay }}</span>
              <span class="unit-area__unit">m²</span>
            </div>

            <div class="unit-breakdown">
              <div
                v-for="row in breakdownRows"
                :key="row.label"
                class="unit-breakdown__row"
                :class="{ 'unit-breakdown__row--emphasis': row.emphasis }"
              >
                <span class="unit-breakdown__label">{{ row.label }}</span>
                <span class="unit-breakdown__value">{{ row.value }} m²</span>
              </div>
            </div>

            <div class="unit-pricing">
              <div class="unit-pricing__row">
                <span class="unit-pricing__label">Cena s DPH</span>
                <span class="unit-pricing__value unit-pricing__value--accent">{{ priceWithVatDisplay }}</span>
              </div>
              <div class="unit-pricing__divider" />
              <div class="unit-pricing__row unit-pricing__row--muted">
                <span class="unit-pricing__label">Cena bez DPH</span>
                <span class="unit-pricing__value">{{ priceWithoutVatDisplay }}</span>
              </div>
            </div>

            <div class="unit-actions">
              <NuxtLink :to="`/contact?unit=${encodeURIComponent(apartment.code)}`" class="btn btn-primary btn-rounded">
                Mám záujem o jednotku
              </NuxtLink>
            </div>
          </aside>
        </div>
      </div>
    </section>

    <ApartmentStandards :slides="standardsSlides" />
  </div>
</template>

<script setup lang="ts">
import ApartmentStandards from '~/components/apartment/ApartmentStandards.vue'

const {
  apartment,
  areaDisplay,
  priceWithVatDisplay,
  priceWithoutVatDisplay,
  breakdownRows,
  standardsSlides,
} = useApartmentDetail()
</script>
