<script setup lang="ts">
import type { PetSummary } from '@/stores/pets.store'

definePageMeta({ middleware: 'auth' })

const petsStore = usePetsStore()
const route = useRoute()
const feedback = ref('')

const petId = computed(() => Number(route.params.id))
const pet = computed<PetSummary | null>(() => petsStore.list.find((entry) => entry.id === petId.value) || null)

const errorMessage = (error: unknown, fallback: string) => {
  if (error && typeof error === 'object' && 'data' in error) {
    const data = (error as { data?: { message?: string } }).data
    if (typeof data?.message === 'string') {
      return data.message as string
    }
  }
  return fallback
}

const hydratePet = async () => {
  try {
    await petsStore.refreshPet(petId.value)
    petsStore.selectPet(petId.value)
  } catch (error) {
    feedback.value = errorMessage(error, 'Unable to load pet')
  }
}

watch(
  () => route.params.id,
  () => {
    hydratePet()
  },
  { immediate: true }
)

const handleAction = async (action: 'feed' | 'clean' | 'play') => {
  feedback.value = ''
  try {
    await petsStore.performAction(petId.value, action)
  } catch (error) {
    feedback.value = errorMessage(error, 'Action blocked')
  }
}

const todayActions = computed(() => pet.value?.actions?.length ?? 0)
</script>

<template>
  <section v-if="pet" class="pet-detail py-4">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
        <div>
          <p class="text-white-50 mb-1">Level {{ pet.level }}</p>
          <h2 class="text-white mb-0">{{ pet.name }}</h2>
        </div>
        <NuxtLink class="btn btn-outline-light" to="/app/dashboard">Back to pets</NuxtLink>
      </div>

      <div v-if="feedback" class="alert alert-warning">{{ feedback }}</div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card glass p-4 text-white mb-4">
            <h5 class="mb-3">Stat overview</h5>
            <div class="row g-3">
              <div class="col-md-4">
                <p class="text-uppercase text-white-50 small mb-1">Hunger</p>
                <div class="progress bg-opacity-25 bg-dark">
                  <div class="progress-bar bg-info" role="progressbar" :style="{ width: pet.hunger + '%' }" />
                </div>
              </div>
              <div class="col-md-4">
                <p class="text-uppercase text-white-50 small mb-1">Hygiene</p>
                <div class="progress bg-opacity-25 bg-dark">
                  <div class="progress-bar bg-success" role="progressbar" :style="{ width: pet.hygiene + '%' }" />
                </div>
              </div>
              <div class="col-md-4">
                <p class="text-uppercase text-white-50 small mb-1">Happiness</p>
                <div class="progress bg-opacity-25 bg-dark">
                  <div class="progress-bar bg-warning" role="progressbar" :style="{ width: pet.happiness + '%' }" />
                </div>
              </div>
            </div>
          </div>

          <div class="card glass p-4 text-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0">Actions</h5>
              <small class="text-white-50">Cooldown aware buttons</small>
            </div>
            <ActionButtons :pet="pet" @perform="handleAction" />
          </div>
        </div>
        <div class="col-lg-4 d-flex flex-column gap-3">
          <StreakBadge label="Daily actions" :value="todayActions + ' / 3'" reward="3-day streak: +5 XP" />
          <StreakBadge label="Weekly wins" value="2 days" reward="7-day streak: +15 XP" />
          <div class="card glass text-white p-3">
            <h6>Recent actions</h6>
            <ul class="list-unstyled small mb-0">
              <li v-for="action in pet.actions" :key="action.id" class="d-flex justify-content-between">
                <span class="text-capitalize">{{ action.type }}</span>
                <span class="text-white-50">{{ new Date(action.created_at).toLocaleTimeString() }}</span>
              </li>
              <li v-if="!pet.actions?.length" class="text-white-50">No actions yet today.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section v-else class="py-5 text-center text-white-50">
    Loading pet…
  </section>
</template>
