<script setup lang="ts">
const props = defineProps<{
  label: string
  value: string
  reward: string
}>()
</script>

<template>
  <div class="p-3 rounded-4 bg-gradient streak">
    <p class="text-uppercase small text-white-50 mb-1">{{ props.label }}</p>
    <h3 class="text-white mb-2">{{ props.value }}</h3>
    <span class="badge bg-dark">{{ props.reward }}</span>
  </div>
</template>
