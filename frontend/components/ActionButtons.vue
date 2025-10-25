<script setup lang="ts">
import type { PetSummary } from '@/stores/pets.store'

type ActionType = 'feed' | 'clean' | 'play'

const props = defineProps<{ pet: PetSummary }>()
const emit = defineEmits<{ (e: 'perform', action: ActionType): void }>()

const cooldowns = ref({ ...props.pet.cooldowns })
let timer: NodeJS.Timeout | null = null

const formatSeconds = (ms: number) => `${Math.ceil(ms / 1000)}s`

const hydrateCooldowns = () => {
  cooldowns.value = { ...props.pet.cooldowns }
}

const startTimer = () => {
  timer = setInterval(() => {
    cooldowns.value = Object.fromEntries(
      Object.entries(cooldowns.value).map(([key, value]) => [key, Math.max(0, Number(value) - 1000)])
    ) as typeof cooldowns.value
  }, 1000)
}

const stopTimer = () => {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

onMounted(() => {
  startTimer()
})

onBeforeUnmount(() => {
  stopTimer()
})

watch(
  () => props.pet.cooldowns,
  () => {
    hydrateCooldowns()
  },
  { deep: true }
)

const buttons = computed(() => [
  {
    type: 'feed' as ActionType,
    label: 'Feed',
    description: 'Hunger +25 / Hygiene -5 / Happy +5',
    variant: 'btn-info'
  },
  {
    type: 'clean' as ActionType,
    label: 'Clean',
    description: 'Hygiene +25 / Happy +5',
    variant: 'btn-success'
  },
  {
    type: 'play' as ActionType,
    label: 'Play',
    description: 'Happy +25 / Hunger -10 / Hygiene -5',
    variant: 'btn-warning text-dark'
  }
])

const disabledReason = (action: ActionType) => {
  const key = `${action}_ms` as keyof typeof cooldowns.value
  const time = cooldowns.value[key]
  return time > 0 ? `Ready in ${formatSeconds(time)}` : 'Ready'
}

const handleClick = (action: ActionType) => {
  const key = `${action}_ms` as keyof typeof cooldowns.value
  if (cooldowns.value[key] > 0) return
  emit('perform', action)
}
</script>

<template>
  <div class="row g-3">
    <div v-for="button in buttons" :key="button.type" class="col-12 col-md-4">
      <div class="p-3 rounded-4 bg-dark h-100 border border-secondary">
        <p class="text-white-50 small text-uppercase mb-1">{{ button.description }}</p>
        <button
          class="btn w-100 fw-semibold"
          :class="button.variant"
          :disabled="cooldowns[`${button.type}_ms`] > 0"
          @click="handleClick(button.type)"
        >
          {{ button.label }} · {{ disabledReason(button.type) }}
        </button>
      </div>
    </div>
  </div>
</template>
