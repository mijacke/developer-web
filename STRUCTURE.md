# Projektová štruktúra

## 📁 Organizácia priečinkov

```
app/
├── assets/              # Statické súbory
│   ├── css/            # Globálne štýly (Tailwind, vlastné CSS)
│   └── fonts/          # Vlastné fonty
│
├── components/          # Vue komponenty
│   ├── contact/        # Kontaktné komponenty (ContactMap.vue)
│   ├── footer/         # Footer komponenty (Footer.vue)
│   ├── home/           # Domovská stránka (Hero, Services, Portfolio Preview, CTA)
│   ├── navigation/     # Navigačné komponenty (Header, MobileMenu, LanguageToggle)
│   └── portfolio/      # Portfolio komponenty
│
├── composables/         # Reusable composition functions
│   ├── useAppCopy.ts   # Aplikačné texty a preklady
│   ├── useHeroAutoScroll.ts
│   ├── useMobileMenu.ts
│   ├── usePortfolio.ts
│   └── useThemeClasses.ts
│
├── layouts/             # Layout wrappery
│   └── default.vue     # Hlavný layout
│
├── pages/               # Routing stránky (Nuxt auto-routing)
│   ├── index.vue       # Domovská stránka (/)
│   ├── about-project/
│   │   └── index.vue   # O projekte (/about-project)
│   ├── locality/
│   │   └── index.vue   # Lokalita (/locality)
│   ├── gallery/
│   │   └── index.vue   # Galéria (/gallery)
│   ├── parking/
│   │   └── index.vue   # Parkovanie (/parking)
│   └── contact/
│       └── index.vue   # Kontakt (/contact)
│
├── types/               # TypeScript definície
│   └── index.ts        # Globálne typy a interfaces
│
├── utils/               # Utility funkcie
│   └── helpers.ts      # Pomocné funkcie
│
├── i18n/                # Internacionalizácia (SK/EN)
│   ├── sk.ts           # Slovenské preklady
│   └── en.ts           # Anglické preklady
│
└── app.vue              # Root komponenta aplikácie
```

## 🎯 Odporúčaná organizácia kódu

### 1. **Components** - UI komponenty
- Rozdeľuj komponenty podľa funkčných oblastí (home, contact, navigation...)
- Každý komponent by mal mať jednu zodpovednosť
- Použitie PascalCase pre názvy súborov

**Príklad:**
```
components/
├── home/
│   ├── HomeHero.vue
│   ├── HomeCategorySection.vue
│   ├── HomeContactCta.vue
│   ├── HomePortfolioPreview.vue
│   └── HomeServices.vue
├── navigation/
│   ├── Header.vue
│   ├── BrandLink.vue
│   ├── HamburgerButton.vue
│   ├── LanguageToggle.vue
│   └── MobileMenu.vue
```

### 2. **Composables** - Reusable logika
- Používaj prefix `use` (napr. `usePortfolio.ts`)
- Vráť reaktívne hodnoty a funkcie
- Izoluj business logiku od UI komponentov

**Príklad:**
```typescript
// composables/usePortfolio.ts
export const usePortfolio = () => {
  const projects = ref([...])
  const filterByCategory = (category: string) => {...}
  
  return {
    projects,
    filterByCategory
  }
}
```

### 3. **Pages** - Routing
- Nuxt automaticky vytvorí routes zo súborov v `pages/`
- `index.vue` = hlavná stránka danej sekcie
- Podpriečinky = vnorené routes

**Príklad:**
```
pages/
├── index.vue              → /
├── about-project/
│   └── index.vue          → /about-project
├── locality/
│   └── index.vue          → /locality
├── gallery/
│   └── index.vue          → /gallery
├── parking/
│   └── index.vue          → /parking
└── contact/
    └── index.vue          → /contact
```

### 4. **Types** - TypeScript definície
```typescript
// types/index.ts
export interface Project {
  id: string
  title: string
  description: string
  image: string
  category: 'web' | 'mobile' | 'design'
}

export interface Service {
  icon: string
  title: string
  description: string
}
```

### 5. **Utils** - Pomocné funkcie
```typescript
// utils/helpers.ts
export const formatDate = (date: Date) => {...}
export const slugify = (text: string) => {...}
export const validateEmail = (email: string) => {...}
```

### 6. **Layouts** - Štruktúra stránok
```vue
<!-- layouts/default.vue -->
<template>
  <div>
    <Header />
    <slot /> <!-- Tu sa vloží obsah stránky -->
    <Footer />
  </div>
</template>
```

## 🔄 Best Practices

### ✅ Separation of Concerns
- **Komponenty** - len UI a prezentačná logika
- **Composables** - business logika, state management
- **Utils** - čisté funkcie bez závislostí
- **Types** - type safety a dokumentácia

### ✅ Naming Conventions
- Komponenty: `PascalCase` (HomeHero.vue)
- Composables: `camelCase` s prefixom `use` (usePortfolio.ts)
- Utils: `camelCase` (formatDate)
- Types/Interfaces: `PascalCase` (Project, Service)

### ✅ Import Aliasy
Nuxt podporuje auto-import, ale môžeš používať:
```typescript
import { usePortfolio } from '~/composables/usePortfolio'
import { Project } from '~/types'
```

## 📝 Ďalšie kroky

1. Pridaj Tailwind CSS súbor do `assets/css/tailwind.css`
2. Vytvor základný layout v `layouts/default.vue`
3. Implementuj komponenty postupne podľa sekcií
4. Vytvor composables pre zdieľanú logiku
5. Definuj TypeScript typy pre konzistenciu

