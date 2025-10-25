export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase
  const sanctumBase = baseURL.replace(/\/?api\/?$/, '')

  const api = $fetch.create({
    baseURL,
    credentials: 'include',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })

  const ensureCsrf = async () => {
    await $fetch(`${sanctumBase}/sanctum/csrf-cookie`, {
      credentials: 'include'
    })
  }

  return {
    provide: {
      api,
      ensureCsrf,
      sanctumBase
    }
  }
})
