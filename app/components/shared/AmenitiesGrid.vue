<template>
  <section class="amenities-section section">
    <div class="container">
      <div class="amenities-grid">
        <div
          v-for="amenity in amenities"
          :key="amenity.id"
          class="amenity-item"
        >
          <div :class="['amenity-icon', `amenity-icon--${amenity.color}`]">
            <img :src="amenity.icon" :alt="amenity.label" width="20" height="20" />
          </div>
          <span class="amenity-label">{{ amenity.label }}</span>
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
</template>

<script setup lang="ts">
/**
 * Shared amenities grid component.
 * Displays nearby services/amenities with icons in a responsive 4-column grid.
 */

import wavesIcon from '~/assets/icons/amenities/waves.svg'
import universityIcon from '~/assets/icons/amenities/university.svg'
import mailIcon from '~/assets/icons/amenities/mail.svg'
import churchIcon from '~/assets/icons/amenities/church.svg'
import babyIcon from '~/assets/icons/amenities/baby.svg'
import treeIcon from '~/assets/icons/amenities/tree-deciduous-white.svg'
import activityIcon from '~/assets/icons/amenities/activity.svg'
import phoneIcon from '~/assets/icons/amenities/phone.svg'
import coffeeIcon from '~/assets/icons/amenities/coffee.svg'
import utensilsIcon from '~/assets/icons/amenities/utensils.svg'
import shoppingBagIcon from '~/assets/icons/amenities/shopping-bag.svg'
import fuelIcon from '~/assets/icons/amenities/fuel.svg'

interface Amenity {
  id: string
  label: string
  icon: string
  color: string
}

const amenities: Amenity[] = [
  { id: 'sport', label: 'Vodné dielo, šport', icon: wavesIcon, color: 'teal' },
  { id: 'schools', label: 'Školy', icon: universityIcon, color: 'green' },
  { id: 'post', label: 'Pošta', icon: mailIcon, color: 'orange' },
  { id: 'church', label: 'Kostol', icon: churchIcon, color: 'brown' },
  { id: 'nursery', label: 'Jasle a škôlky', icon: babyIcon, color: 'red' },
  { id: 'parks', label: 'Parky', icon: treeIcon, color: 'lime' },
  { id: 'pharmacy', label: 'Lekárne', icon: activityIcon, color: 'emerald' },
  { id: 'client-center', label: 'Klientske centrum', icon: phoneIcon, color: 'blue' },
  { id: 'cafes', label: 'Kaviarne', icon: coffeeIcon, color: 'cyan' },
  { id: 'restaurants', label: 'Reštaurácie', icon: utensilsIcon, color: 'purple' },
  { id: 'shops', label: 'Obchody', icon: shoppingBagIcon, color: 'gold' },
  { id: 'gas-stations', label: 'Čerpacie stanice', icon: fuelIcon, color: 'salmon' },
]
</script>
