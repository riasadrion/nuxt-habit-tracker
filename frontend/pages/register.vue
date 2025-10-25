<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const handleSubmit = async () => {
  try {
    await auth.register(form)
    router.push('/app/dashboard')
  } catch {
    // handled upstream
  }
}
</script>

<template>
  <section class="auth-wrapper py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card glass text-white p-4">
            <h2 class="mb-3">Create an account</h2>
            <p class="text-white-50">Start caring for your first pet in minutes.</p>
            <form class="mt-4" @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="form.name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input v-model="form.password" type="password" class="form-control" required minlength="8" />
              </div>
              <div class="mb-3">
                <label class="form-label">Confirm password</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  class="form-control"
                  required
                  minlength="8"
                />
              </div>
              <p v-if="auth.error" class="text-danger small">{{ auth.error }}</p>
              <button class="btn btn-info w-100" :disabled="auth.status === 'loading'">
                {{ auth.status === 'loading' ? 'Creating…' : 'Create account' }}
              </button>
            </form>
            <p class="mt-3 text-white-50">
              Already registered?
              <NuxtLink to="/login" class="text-info">Sign in</NuxtLink>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
