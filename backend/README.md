# Virtual Pet Habit Tracker API

Laravel 12 API that powers the Nuxt SPA. It exposes Sanctum-protected endpoints for authentication, pet management, and habit actions.

## Getting Started

1. Copy `.env` and update your MySQL credentials.
2. Install dependencies: `composer install`.
3. Generate an app key: `php artisan key:generate`.
4. Run migrations and seed demo data: `php artisan migrate --seed`.
5. Start the dev server: `php artisan serve` (defaults to `http://localhost:8000`).

The demo seeder creates `demo@pets.test / password` with a pair of pets.

## Available Commands

- `php artisan pets:decay` – runs every 15 minutes via scheduler to decay idle stats.
- `php artisan pets:daily-summary` – snaps daily habit summaries (scheduled 23:59).
- `php artisan schedule:work` – keep the decay + summary loops running.
- `php artisan queue:work` – process queued jobs if you plug in async broadcasting later.

## Key Endpoints

All endpoints live under `/api` and require Sanctum session cookies after `POST /login`.

| Method | URI | Description |
| --- | --- | --- |
| POST | `/register` | Create an account + start a session. |
| POST | `/login` | Log in (remember to hit `/sanctum/csrf-cookie` first from the SPA). |
| POST | `/logout` | Destroy the session. |
| GET | `/user` | Return the authenticated user payload. |
| GET | `/pets` | List your pets with stat summaries. |
| POST | `/pets` | Create a new pet (rate limited to 10/day). |
| GET | `/pets/{id}` | Show a single pet. |
| PATCH | `/pets/{id}` | Rename or change species. |
| DELETE | `/pets/{id}` | Soft delete a pet. |
| POST | `/pets/{id}/feed` | Execute the feed loop. |
| POST | `/pets/{id}/clean` | Execute the clean loop. |
| POST | `/pets/{id}/play` | Execute the play loop. |
| GET | `/pets/{id}/actions` | Paginated action history. |

Responses use the shape outlined in `App\Http\Resources\PetResource` and include cooldown timers plus the latest action logs for a quick UI sync.

## Domain Notes

- Policies lock every pet to its owner.
- `PetActionService` wraps stat changes, cooldown enforcement, XP gains, and events in a transaction.
- Cooldowns (2/3/4 min) emit HTTP 429 when violated so the SPA can show timers.
- Daily summaries and streak math live in `DailySummary` rows updated by listeners + scheduled commands.
- Sanctum is configured for SPA mode (`statefulApi`, cookie session driver, and CORS allowing `http://localhost:3000`).

## Testing

Use `php artisan test` after configuring a test database. Feature tests for cooldowns, decay, and policies can be added under `tests/Feature` following the provided service architecture.
