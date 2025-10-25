import { defineStore } from 'pinia'

export type CooldownTimers = {
  feed_ms: number
  clean_ms: number
  play_ms: number
}

export interface PetActionLog {
  id: number
  type: 'feed' | 'clean' | 'play'
  delta_hunger: number
  delta_hygiene: number
  delta_happiness: number
  xp_awarded: number
  created_at: string
}

export interface PetSummary {
  id: number
  name: string
  species: string
  level: number
  xp: number
  hunger: number
  hygiene: number
  happiness: number
  last_interaction_at: string | null
  cooldowns: CooldownTimers
  actions?: PetActionLog[]
}

interface PetPayload {
  name: string
  species: string
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

export const usePetsStore = defineStore('pets', {
  state: () => ({
    list: [] as PetSummary[],
    activePetId: null as number | null,
    isLoading: false,
    error: '' as string
  }),
  getters: {
    activePet(state) {
      return state.list.find((pet) => pet.id === state.activePetId) || null
    }
  },
  actions: {
    upsertPet(pet: PetSummary) {
      const index = this.list.findIndex((existing: PetSummary) => existing.id === pet.id)
      if (index >= 0) {
        this.list[index] = pet
      } else {
        this.list.unshift(pet)
      }
      if (!this.activePetId) {
        this.activePetId = pet.id
      }
    },
    async loadPets(force = false) {
      if (this.list.length && !force) {
        return
      }

      this.isLoading = true
      this.error = ''

      try {
        const { $api } = useNuxtApp()
        const response = await $api<{ pets: PetSummary[] }>('/pets')
        this.list = response.pets
        const first = this.list[0]
        if (first && !this.activePetId) {
          this.activePetId = first.id
        }
      } catch (error) {
        this.error = extractErrorMessage(error, 'Unable to load pets')
        throw error
      } finally {
        this.isLoading = false
      }
    },
    async createPet(payload: PetPayload) {
      const { $api } = useNuxtApp()
      this.error = ''
      try {
        const pet = await $api<PetSummary>('/pets', {
          method: 'POST',
          body: payload
        })
        this.upsertPet(pet)
        this.activePetId = pet.id
      } catch (error) {
        this.error = extractErrorMessage(error, 'Unable to create pet')
        throw error
      }
    },
    async performAction(petId: number, action: 'feed' | 'clean' | 'play') {
      const { $api } = useNuxtApp()
      const pet = await $api<PetSummary>(`/pets/${petId}/${action}`, {
        method: 'POST'
      })
      this.upsertPet(pet)
    },
    async refreshPet(petId: number) {
      const { $api } = useNuxtApp()
      const pet = await $api<PetSummary>(`/pets/${petId}`)
      this.upsertPet(pet)
    },
    async deletePet(petId: number) {
      const { $api } = useNuxtApp()
      await $api(`/pets/${petId}`, { method: 'DELETE' })
      this.list = this.list.filter((candidate: PetSummary) => candidate.id !== petId)
      if (this.activePetId === petId) {
        this.activePetId = this.list[0]?.id ?? null
      }
    },
    selectPet(petId: number) {
      this.activePetId = petId
    },
    reset() {
      this.list = []
      this.activePetId = null
      this.error = ''
    }
  }
})
