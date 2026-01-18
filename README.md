# Rezidencia Žilina - Developer Web

Webová aplikácia pre developerský projekt Rezidencia Žilina s interaktívnou mapou bytov a admin rozhraním.

## 🏗️ Technológie

| Vrstva | Technológia |
|--------|-------------|
| **Frontend** | Nuxt.js 3, Vue 3, TypeScript |
| **Backend** | Laravel 12, PHP 8.2+ |
| **Databáza** | MariaDB / MySQL |
| **Autentifikácia** | Laravel Sanctum (API Tokens) |
| **Kontajnerizácia** | Docker, Docker Compose |

## 📋 Požiadavky

- Docker & Docker Compose
- Node.js 18+ (pre lokálny vývoj)
- PHP 8.2+ (pre lokálny vývoj)
- Composer (pre lokálny vývoj)

## 🚀 Inštalácia (Docker)

### 1. Klonovanie repozitára

```bash
git clone <repository-url>
cd developer-web
```

### 2. Nastavenie environment premenných

```bash
# Backend
cp backend/.env.example backend/.env
```

Upravte `backend/.env`:
```env
DB_CONNECTION=mariadb
DB_HOST=db
DB_PORT=3306
DB_DATABASE=rezidencia_zilina
DB_USERNAME=laravel
DB_PASSWORD=secret

APP_FRONTEND_URL=http://localhost:3000
```

### 3. Spustenie Docker kontajnerov

```bash
docker-compose up -d
```

### 4. Inštalácia závislostí a migrácie

```bash
# Backend
docker exec rezidencia_backend composer install
docker exec rezidencia_backend php artisan key:generate
docker exec rezidencia_backend php artisan migrate --seed

# Frontend
docker exec rezidencia_frontend npm install
```

### 5. Prístup k aplikácii

| Služba | URL |
|--------|-----|
| Frontend | http://localhost:3000 |
| Backend API | http://localhost:8000/api |
| phpMyAdmin | http://localhost:8080 |

## 👤 Predvolené účty

| Email | Heslo | Rola |
|-------|-------|------|
| admin@example.com | password | admin |
| user@example.com | password | user |

## 📁 Štruktúra projektu

```
developer-web/
├── app/                    # Nuxt.js frontend
│   ├── assets/styles/      # CSS štýly
│   ├── components/         # Vue komponenty
│   ├── composables/        # Composables (useAuth, etc.)
│   ├── layouts/            # Layouty (default, admin)
│   ├── middleware/         # Route middleware
│   └── pages/              # Stránky
├── backend/                # Laravel backend
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/   # API kontroléry
│   │   │   ├── Middleware/        # Custom middleware
│   │   │   └── Requests/          # Form Request validácia
│   │   ├── Models/                # Eloquent modely
│   │   └── Traits/                # Auditable trait
│   ├── database/
│   │   ├── migrations/            # DB migrácie
│   │   └── seeders/               # Seedery
│   └── routes/api.php             # API routes
├── public/                 # Statické súbory
└── docker-compose.yml      # Docker konfigurácia
```

## 🔐 API Endpointy

### Verejné
| Metóda | Endpoint | Popis |
|--------|----------|-------|
| POST | `/api/contact` | Kontaktný formulár |
| POST | `/api/auth/login` | Prihlásenie |
| POST | `/api/auth/register` | Registrácia |
| POST | `/api/auth/forgot-password` | Reset hesla |

### Chránené (vyžaduje token)
| Metóda | Endpoint | Popis |
|--------|----------|-------|
| GET | `/api/projects` | Zoznam projektov |
| GET | `/api/projects/{id}/localities` | Lokality projektu |
| POST | `/api/auth/logout` | Odhlásenie |

### Admin (vyžaduje admin rolu)
| Metóda | Endpoint | Popis |
|--------|----------|-------|
| GET | `/api/admin/stats` | Dashboard štatistiky |
| GET | `/api/admin/users` | Správa používateľov |
| GET | `/api/admin/contact-messages` | Kontaktné správy |
| GET | `/api/admin/contact-stats` | Štatistiky správ |

## 🛡️ Bezpečnostné funkcie

- **Password Hashing** - bcrypt
- **SQL Injection Protection** - Eloquent ORM
- **Rate Limiting** - 5 req/min na login, 3 req/hod na reset hesla
- **CSRF Protection** - Laravel Sanctum
- **Role-based Access Control** - admin/user role
- **Audit Logging** - všetky zmeny sa logujú

## 🧪 Testovanie

```bash
# Spustenie testov
docker exec rezidencia_backend php artisan test

# Seed testovacie dáta
docker exec rezidencia_backend php artisan db:seed --class=ContactMessageSeeder
```

## 📧 Email konfigurácia

Pre odosielanie emailov (reset hesla) nastavte v `backend/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Rezidencia Žilina"
```

## 📝 Licencia

Tento projekt je určený pre akademické účely.

---

**Autor:** Mario Lassu  
**Predmet:** VAII - Vývoj aplikácií pre internet  
**Rok:** 2026
