# Codex Agent Configuration

## Project Overview
- **Project name:** Virtual Pet Habit Tracker
- **Summary:** Users create virtual pets and keep them healthy by feeding, cleaning, and playing. The product is a Nuxt SPA backed by a Laravel API secured with Sanctum.

## Repository Layout
- `root`: `/nuxt-habit-tracker`
- `frontend`: `/frontend`
- `backend`: `/backend`

## Technology Stack
### Frontend
- Framework: Nuxt 4 (SPA mode)
- Language: JavaScript / TypeScript friendly
- UI: Bootstrap CSS
- State: Pinia
- Package manager: npm
- Commands:
  - `npm run dev`
  - `npm run build`
  - `npm run lint`

### Backend
- Framework: Laravel 12
- Language: PHP 8.4
- Database: MySQL
- Cache: Redis
- Queue: database driver
- Auth: Sanctum (SPA guard)
- Package manager: Composer
- Commands:
  - `php artisan serve`
  - `php artisan migrate`
  - `php artisan db:seed`
  - `php artisan queue:work`
  - `php artisan schedule:work`
  - `php artisan test`

## Authentication & Session
- Flow: Sanctum SPA with `/sanctum/csrf-cookie`
- CORS: enabled
- Cookies: `SameSite=lax`, `HttpOnly`, `Secure=false` (set true for HTTPS)
- Auth routes: `POST /login`, `POST /register`, `POST /logout`, `GET /user`
- Frontend guard: redirect unauthenticated users away from `/app/*`
- Store authenticated user in Pinia after successful login

## Data Model (Minimal)
### users
- `id`, `name`, `email` (unique), `password`, timestamps

### pets
- `id`, `user_id` (FK referencing users)
- `name`, `species` (enum: cat, dog, fox, dragon, other)
- `level` (default 1), `xp` (default 0)
- `hunger`, `hygiene`, `happiness` (0-100, defaults 60)
- `last_interaction_at`, timestamps
- Index on `user_id`

### pet_actions
- `id`, `pet_id`
- `type` (feed, clean, play)
- `delta_hunger`, `delta_hygiene`, `delta_happiness`
- `xp_awarded`, `metadata` (JSON), `created_at`

### daily_summaries
- `id`, `pet_id`, `date`
- `habits_completed`, `mood_avg`, `created_at`

**Constraint:** pets must belong to the authenticated user (policy enforced).

## Game Mechanics
- XP per action: 10
- Level curve: `level_up_at = 100 * current_level`
- On level up: raise soft cap on mood stats and emit `PetLeveledUp`
- Actions:
  - Feed: hunger +25, hygiene -5, happiness +5, XP +10, 2 min cooldown
  - Clean: hygiene +25, happiness +5, XP +10, 3 min cooldown
  - Play: happiness +25, hunger -10, hygiene -5, XP +10, 4 min cooldown
- Stat caps: clamp between 0 and 100; decay job lowers idle pets
- Streaks: track daily completions per pet (3-day +5 XP, 7-day +15 XP repeating)
- Cooldowns enforced per pet per action using latest `pet_actions.created_at`

## API Design
- Base URL: `http://localhost:8000/api`
- Content type: JSON

### Public/Auth
- `POST /register {name,email,password,password_confirmation}`
- `POST /login {email,password}`
- `POST /logout`
- `GET /user`

### Pets
- `GET /pets` lists authenticated user pets
- `POST /pets {name,species}` creates a pet
- `GET /pets/{id}`
- `PATCH /pets/{id}` renames or changes species
- `DELETE /pets/{id}` soft deletes (optional)

### Actions
- `POST /pets/{id}/feed`
- `POST /pets/{id}/clean`
- `POST /pets/{id}/play`
- `GET /pets/{id}/actions` returns paginated history

### Response Shape (pet)
`{ id, name, species, level, xp, hunger, hygiene, happiness, last_interaction_at, cooldowns: { feed_ms, clean_ms, play_ms } }`

### Errors
- `403` when accessing another user's pet
- `429` when action cooldown not finished
- `422` for validation failures

## Agent Preferences
- Ignore: `node_modules/`, `vendor/`, `storage/`, `.git/`, `.nuxt/`, `public/storage/`, `tests/`
- Code style: PHP = PSR-12; JS = ESLint Standard + Prettier
- Personality: direct, practical, forward-thinking
- Focus areas:
  - REST APIs with policies + form requests
  - Cooldown & decay correctness
  - Idempotent actions (safe refresh)
  - Simple, testable domain services

## Backend Tasks & Events
- Scheduled commands:
  - `pets:decay` every 15 minutes
  - `pets:daily-summary` at 23:59
- Events: `PetActionPerformed`, `PetLeveledUp`
- Listeners: `UpdatePetStats`, `BroadcastPetStats` (optional websockets)
- Policies: `PetPolicy@view/update/delete` ensures ownership
- Tests to keep: `ActionCooldownTest`, `DecayJobTest`, `LevelUpCurveTest`, `PolicyEnforcementTest`

## Frontend UX
- Pages: `/login`, `/register`, `/app/dashboard`, `/app/pets/:id`
- Components: `PetCard.vue`, `ActionButtons.vue`, `StreakBadge.vue`
- State stores: `auth.store` (user, isLoggedIn), `pets.store` (list, activePet, actions, refresh)
- UI notes:
  - Optimistic updates for actions, reconcile with API payloads
  - Show cooldown timers with `setInterval`
  - Disable action buttons when cooldown > 0

## Rate Limits (Optional)
- Actions: 15 requests/min per user
- Pet creation: 10 requests/day per user

## Seed Data
- Demo user with credentials for QA
- 1-2 pets seeded at mid stats
- Historical `pet_actions` to populate charts

## Definition of Done
- Auth (register/login/logout) operational via Sanctum
- Users can create/list/view their own pets
- Feed/Clean/Play endpoints enforce cooldowns and adjust stats atomically
- Decay job reduces stats over time
- XP + level up emit events and update caps
- Nuxt UI reflects real-time stat changes and disables buttons during cooldown
