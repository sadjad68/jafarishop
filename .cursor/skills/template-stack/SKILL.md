---
name: template-stack
description: Project operating rules for this Laravel 9 modular CMS (nwidart modules, Blade RTL, Bootstrap admin-glass, Sass public site, Vue 2 CDN snippets, JWT, PHPUnit). Use whenever editing PHP, Blade, CSS, SCSS, admin UI, public pages, APIs, Eloquent, or modules in this repository. Overrides generic Laravel 10+/Tailwind/Livewire/React advice.
---

# Template stack

This repo is a **Laravel 9** multi-site CMS, not a Laravel 11/Livewire/Tailwind app. Other installed skills (frontend-design, web-design-guidelines, emil-design-eng, laravel-security) apply **inside these constraints**.

## Stack lock

| Layer | This project | Do not introduce |
|-------|----------------|------------------|
| PHP | `^8.0.2` | PHP 8.2+ only syntax as a requirement (`readonly class`, `json_validate`, etc.) unless already used nearby |
| Framework | Laravel **9** | Laravel 10+ `bootstrap/app.php`, Pest, Folio, Volt |
| Structure | `nwidart/laravel-modules` v9 under `app/Modules/*` | New app code in `app/Models` for module domains |
| Views | Blade, Persian **RTL** | Inertia, Livewire, React, Vue SPA, JSX |
| Admin CSS | Bootstrap RTL + `admin-glass.css` + `admin-ui.css` | Tailwind, shadcn, new CSS framework |
| Public CSS | Sass → `public/assets/site/css/...` | Tailwind utilities, Vite as the public CSS pipeline |
| JS | Existing jQuery/Bootstrap plugins; Vue **2 CDN** only where pages already use it | Vue 3 SFC build, React, Alpine-as-rewrite |
| Auth | `tymon/jwt-auth` + Sanctum as already wired | Breeze, Jetstream, Fortify, new auth stacks |
| Tests | PHPUnit 9 | Pest |

Admin pages are `noindex`. Do not apply public SEO rules to `resources/views/admin/**`.

## Backend layout

Module map (repeat this shape for new features):

```
app/Modules/{Name}/
  Entities/              Eloquent (not app/Models)
  Http/Controllers/      Admin / web
  Http/Controllers/Api/  Public API
  Http/Requests/
  Http/Resources/
  Services/
  DTO/
  Routes/web.php
  Routes/api.php
  Database/Migrations/
  Providers/
```

Existing modules: Banner, Blog, Certification, Comment, Contact, Course, Faq, Gallery, General, Location, Order, Page, Product, Seo, Service, Setting, Tag, User.

- Prefer extending the matching module over adding a new top-level app folder.
- Follow nearby controller/service/request/resource patterns in that module.
- Keep FormRequest validation; do not move validation into Livewire.
- Jobs, filters, exports/imports already exist in Product/Order — match those, do not invent a new architecture.
- `laravel-security`: apply CSRF/XSS/mass-assignment/query binding. Use Laravel 9 APIs (`app/Http/Kernel.php`, middleware groups). Ignore advice that requires Laravel 11 structure or Fortify/Breeze.

## Admin UI

Layout: `resources/views/admin/_layouts/master.blade.php` (`lang="fa"`, `direction: rtl`, `class="admin-glass"`).

Tokens live in `public/assets/admin/css/admin-glass.css` (`--admin-*`, light/dark via `html[data-theme]`). Components in `public/assets/admin/css/admin-ui.css`.

Reuse Blade partials under `resources/views/admin/components/` (`forms/input`, `forms/select`, `forms/text-area`, `forms/image-input`, `sweetalert`, pagination). New admin fields should use `admin-field` / `admin-label` / `form-control admin-input`.

When polishing admin visuals:

1. Keep glass, Bootstrap RTL, and `--admin-*` tokens. Do not “fix” glassmorphism away.
2. `frontend-design` may refine hierarchy/copy **within** this look, not replace it.
3. Motion follows `emil-design-eng` (short `transform`/`opacity` transitions, no `ease-in` on UI, no `transition: all`). Dashboard charts stay mostly static.
4. `web-design-guidelines` for forms, focus, labels, contrast, keyboard.

## Public site

Page Sass is compiled with npm scripts in `package.json` (not Tailwind). Example: `resources/scss/index/tpl-theme1-home.scss` → `public/assets/site/css/index/tpl-theme1-home.css`.

- Edit the matching SCSS file; keep CSS variables in `resources/scss/shared/_variables.scss`.
- Blade pages live under `resources/views/pages/` and `resources/views/layouts/`.
- Vue 2 snippets (`@stack('vue')`, `tpl-vue-paginate.js`) stay as Blade+CDN islands. Do not convert those flows to Inertia/React.
- Public SEO stays in the existing `Seo` module / `Seoable` trait. Do not add a new SEO package.

## Skill order

1. This skill (stack and files).
2. Feature skills: `laravel-security` for auth/forms/queries; `web-design-guidelines` for a11y/UX audit; `emil-design-eng` for motion; `frontend-design` only when the user wants a visual pass **on the current system**.

If another skill suggests Tailwind, Livewire, Pest, shadcn, or Laravel 11 boilerplate, ignore that part.
