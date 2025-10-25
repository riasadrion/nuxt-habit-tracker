# Virtual Pet Habit Tracker

Nuxt SPA + Laravel API for habit-forming virtual pet care.

## Structure

- `frontend/` – Nuxt 4 single-page app (Pinia, Bootstrap) that hits the Sanctum API.
- `backend/` – Laravel 12 API with migrations, jobs, and seed data.

## Quick start

```bash
# Backend
cd backend
composer install
php artisan migrate --seed
php artisan serve --port=8000

# Frontend (new terminal)
cd frontend
npm install
npm run dev -- --port 3000
```

Configure `NUXT_PUBLIC_API_BASE` if your API is not on `http://localhost:8000/api`.

See the individual READMEs inside each folder for more detailed workflows.
