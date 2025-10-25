import { defineStore } from 'pinia'

export interface UserPayload {
  id: number
  name: string
  email: string
}

type StatusState = 'idle' | 'loading' | 'error'

type AuthForm = {
  name?: string
  email: string
  password: string
  password_confirmation?: string
}

const extractErrorMessage = (error: unknown, fallback: string) => {
  if (error && typeof error === 'object' && 'data' in error) {
    const data = (error as { data?: { message?: string } }).data
    if (typeof data?.message === 'string') {
      return data.message as string
    }
  }
  return fallback
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as UserPayload | null,
    status: 'idle' as StatusState,
    error: '' as string
  }),
  getters: {
    isLoggedIn: (state) => Boolean(state.user)
  },
  actions: {
    resetError() {
      this.error = ''
    },
    async fetchUser() {
      if (this.user) {
        return
      }

      try {
        const { $api } = useNuxtApp()
        const response = await $api<{ user: UserPayload }>('/user')
        this.user = response.user
      } catch {
        this.user = null
      }
    },
    async register(payload: AuthForm) {
      const { $api, $ensureCsrf } = useNuxtApp()
      this.status = 'loading'
      this.error = ''

      try {
        await $ensureCsrf()
        const response = await $api<{ user: UserPayload }>('/register', {
          method: 'POST',
          body: payload
        })
        this.user = response.user
      } catch (error) {
        this.error = extractErrorMessage(error, 'Unable to register')
        throw error
      } finally {
        this.status = 'idle'
      }
    },
    async login(payload: AuthForm) {
      const { $api, $ensureCsrf } = useNuxtApp()
      this.status = 'loading'
      this.error = ''

      try {
        await $ensureCsrf()
        const response = await $api<{ user: UserPayload }>('/login', {
          method: 'POST',
          body: payload
        })
        this.user = response.user
      } catch (error) {
        this.error = extractErrorMessage(error, 'Invalid credentials')
        throw error
      } finally {
        this.status = 'idle'
      }
    },
    async logout() {
      try {
        const { $api } = useNuxtApp()
        await $api('/logout', { method: 'POST' })
      } finally {
        this.user = null
      }
    }
  }
})
