# Admin Map Editor CRUD Cheat-Sheet

Toto je najkratšia trasa, ktorú ukážeš na obhajobe.

## 1) Frontend (admin editor)

Súbor:
- `/Users/mariolassu/code/skola/developer-web/public/lib/developer-map-v2/assets/js/admin/app.js`

Jasné CRUD helpery:
- `syncProjectCrud(project)`
- `deleteProjectCrud(project)`
- `syncLocalityCrud(project, locality)`
- `deleteLocalityCrud(project, locality)`

CRUD endpointy volané cez client:
- `storage.createProject(...)`
- `storage.updateProject(...)`
- `storage.deleteProject(...)`
- `storage.createLocality(...)`
- `storage.updateLocality(...)`
- `storage.deleteLocality(...)`

Súbor clienta:
- `/Users/mariolassu/code/skola/developer-web/public/lib/developer-map-v2/assets/js/admin/storage-client.js`

Poznámka:
- Editor stále používa aj `saveProjects(...)` ako batch fallback (`dm-projects`), aby sa nerozbili legacy scenáre.

## 2) API routes

Súbor:
- `/Users/mariolassu/code/skola/developer-web/backend/routes/api.php`

Project CRUD:
- `POST /api/projects`
- `GET /api/projects`
- `GET /api/projects/{project}`
- `PUT /api/projects/{project}`
- `DELETE /api/projects/{project}`

Locality CRUD:
- `POST /api/projects/{project}/localities`
- `GET /api/projects/{project}/localities`
- `GET /api/projects/{project}/localities/{locality}`
- `PUT /api/projects/{project}/localities/{locality}`
- `DELETE /api/projects/{project}/localities/{locality}`

## 3) Backend controllers -> services

Project:
- Controller: `/Users/mariolassu/code/skola/developer-web/backend/app/Http/Controllers/Api/ProjectController.php`
- Service: `/Users/mariolassu/code/skola/developer-web/backend/app/Services/ProjectCrudService.php`
- CRUD service metódy: `create`, `list`, `show`, `update`, `delete`

Locality:
- Controller: `/Users/mariolassu/code/skola/developer-web/backend/app/Http/Controllers/Api/LocalityController.php`
- Service: `/Users/mariolassu/code/skola/developer-web/backend/app/Services/LocalityCrudService.php`
- CRUD service metódy: `create`, `list`, `show`, `update`, `delete`

## 4) Ako to ukázať za 30 sekúnd

1. V admin UI pridaj mapu / uprav mapu / vymaž mapu.
2. V DevTools Network ukáž request na `/api/projects...` alebo `/api/projects/{project}/localities...`.
3. Otvor `storage-client.js` a ukáž metódu (`createProject`/`updateProject`/`deleteProject`).
4. Otvor `api.php` route.
5. Otvor controller metódu.
6. Otvor service metódu (tam je reálna CRUD operácia nad modelom).
