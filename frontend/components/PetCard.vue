<script setup lang="ts">
import type { PetSummary } from '@/stores/pets.store'

const props = defineProps<{
  pet: PetSummary
  isActive?: boolean
}>()

const emit = defineEmits<{ (e: 'select', id: number): void }>()

const progressWidth = (value: number) => `${Math.max(0, Math.min(100, value))}%`
</script>

<template>
  <div class="card glass text-white h-100">
    <div class="card-body d-flex flex-column gap-3">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <p class="text-uppercase small text-info mb-1">Level {{ pet.level }}</p>
          <h4 class="mb-0">{{ pet.name }}</h4>
          <span class="badge bg-secondary">{{ pet.species }}</span>
        </div>
        <button class="btn btn-outline-info" @click="emit('select', pet.id)">
          {{ props.isActive ? 'Viewing' : 'Open' }}
        </button>
      </div>
      <div>
        <p class="small text-uppercase text-white-50 mb-1">Hunger</p>
        <div class="progress bg-opacity-25 bg-dark">
          <div class="progress-bar bg-info" role="progressbar" :style="{ width: progressWidth(pet.hunger) }" />
        </div>
      </div>
      <div>
        <p class="small text-uppercase text-white-50 mb-1">Hygiene</p>
        <div class="progress bg-opacity-25 bg-dark">
          <div class="progress-bar bg-success" role="progressbar" :style="{ width: progressWidth(pet.hygiene) }" />
        </div>
      </div>
      <div>
        <p class="small text-uppercase text-white-50 mb-1">Happiness</p>
        <div class="progress bg-opacity-25 bg-dark">
          <div class="progress-bar bg-warning" role="progressbar" :style="{ width: progressWidth(pet.happiness) }" />
        </div>
      </div>
    </div>
  </div>
</template>
