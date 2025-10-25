declare module '#app' {
  interface NuxtApp {
    $api: typeof $fetch
    $ensureCsrf: () => Promise<void>
    $sanctumBase: string
  }
}

declare module 'vue' {
  interface ComponentCustomProperties {
    $api: typeof $fetch
    $ensureCsrf: () => Promise<void>
    $sanctumBase: string
  }
}

export {}
