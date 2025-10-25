# Virtual Pet Habit Tracker – Frontend

Nuxt 4 SPA that consumes the Sanctum-protected Laravel API. It ships with Pinia stores, Bootstrap styling, auth middleware, and pet dashboards.

## Scripts

| Command | Description |
| --- | --- |
| `npm run dev` | Start the Vite dev server (defaults to `http://localhost:3000`). |
| `npm run build` | Production build. |
| `npm run preview` | Preview the production output locally. |
| `npm run lint` | ESLint in flat-config mode. |
| `npm run typecheck` | Type-check Vue + TS via `vue-tsc`. |

## Environment

- `NUXT_PUBLIC_API_BASE` → URL to the Laravel API (default `http://localhost:8000/api`).
- The Sanctum cookie flow expects the API and SPA to share the same top-level domain (e.g. `localhost`).

## Features

- Auth pages (`/login`, `/register`) call `/sanctum/csrf-cookie` automatically before hitting the API.
- Route middleware guards `/app/*` paths and redirects guests back to `/login`.
- Dashboard lists pets with live cooldown timers and quick-create form.
- Pet detail view renders stat progress bars, optimistic action buttons, and recent history.
- UI components live inside `components/` (`PetCard`, `ActionButtons`, `StreakBadge`, `AppNavbar`).
