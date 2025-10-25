<script setup lang="ts">
const auth = useAuthStore()
const petsStore = usePetsStore()
const router = useRouter()

onMounted(() => {
  if (!auth.user) {
    auth.fetchUser()
  }
})

const handleLogout = async () => {
  await auth.logout()
  petsStore.reset()
  router.push('/')
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-3">
    <div class="container">
      <NuxtLink class="navbar-brand fw-bold" to="/">
        <span class="text-info">Habit</span> Pets
      </NuxtLink>
      <button
        type="button"
        class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu"
        aria-controls="navMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon" />
      </button>
      <div id="navMenu" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
          <li class="nav-item">
            <NuxtLink class="nav-link" to="/app/dashboard">Dashboard</NuxtLink>
          </li>
          <li v-if="!auth.isLoggedIn" class="nav-item">
            <NuxtLink class="nav-link" to="/login">Login</NuxtLink>
          </li>
          <li v-if="!auth.isLoggedIn" class="nav-item">
            <NuxtLink class="nav-link" to="/register">Register</NuxtLink>
          </li>
          <li v-if="auth.isLoggedIn" class="nav-item ms-lg-3">
            <button class="btn btn-outline-light" @click="handleLogout">Logout</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
