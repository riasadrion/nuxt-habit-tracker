<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const form = reactive({
  email: '',
  password: ''
})

const handleSubmit = async () => {
  try {
    await auth.login(form)
    const redirect = (route.query.redirect as string) || '/app/dashboard'
    router.push(redirect)
  } catch {
    // handled by store
  }
}
</script>

<template>
  <section class="auth-wrapper py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card glass text-white p-4">
            <h2 class="mb-3">Welcome back</h2>
            <p class="text-white-50">Log in to keep your pets thriving.</p>
            <form class="mt-4" @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input v-model="form.password" type="password" class="form-control" required />
              </div>
              <p v-if="auth.error" class="text-danger small">{{ auth.error }}</p>
              <button class="btn btn-info w-100" :disabled="auth.status === 'loading'">
                {{ auth.status === 'loading' ? 'Signing in…' : 'Sign in' }}
              </button>
            </form>
            <p class="mt-3 text-white-50">
              Need an account?
              <NuxtLink to="/register" class="text-info">Create one</NuxtLink>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
