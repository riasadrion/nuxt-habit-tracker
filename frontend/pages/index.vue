<script setup lang="ts">
const featureColumns = [
  {
    title: 'Care Loop',
    items: ['Feed, clean & play loops', 'Live cooldown timers', 'Stat decay warnings']
  },
  {
    title: 'Progress',
    items: ['XP + streak tracking', 'Level-based boosts', 'Daily summaries']
  },
  {
    title: 'Safety',
    items: ['Sanctum-protected API', 'Per-pet policies', 'Optimistic UI sync']
  }
]
</script>

<template>
  <main>
    <div class="container py-5">
      <div class="row g-5 align-items-center">
        <div class="col-lg-6 text-center text-lg-start">
          <p class="text-uppercase fw-semibold text-info mb-2">Virtual Pet Habit Tracker</p>
          <h1 class="display-4 fw-bold text-white">
            Build healthy routines by keeping every pet happy.
          </h1>
          <p class="lead text-white-50 mb-4">
            Nuxt SPA + Laravel API. One codebase where habit tracking feels like a cozy pet sim.
          </p>
          <div class="d-flex flex-column flex-sm-row gap-3">
            <NuxtLink class="btn btn-info btn-lg text-white fw-semibold px-4" to="/login">
              Enter Dashboard
            </NuxtLink>
            <a class="btn btn-outline-light btn-lg fw-semibold px-4" href="/backend/README.md" target="_blank">
              Preview API contract
            </a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="row g-4">
            <div v-for="column in featureColumns" :key="column.title" class="col-12 col-sm-4">
              <div class="bg-dark rounded-4 p-4 h-100 shadow">
                <p class="text-info text-uppercase small fw-semibold mb-2">{{ column.title }}</p>
                <ul class="list-unstyled text-white-50 mb-0">
                  <li v-for="item in column.items" :key="item" class="mb-2 d-flex gap-2">
                    <span aria-hidden="true">•</span>
                    <span>{{ item }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
