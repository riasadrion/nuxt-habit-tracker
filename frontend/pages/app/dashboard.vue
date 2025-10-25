<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const petsStore = usePetsStore()
const auth = useAuthStore()

const form = reactive({
  name: '',
  species: 'cat'
})

const speciesOptions = ['cat', 'dog', 'fox', 'dragon', 'other']

onMounted(() => {
  petsStore.loadPets()
})

const handleCreate = async () => {
  if (!form.name.trim()) return
  await petsStore.createPet({ ...form })
  form.name = ''
}
</script>

<template>
  <section class="dashboard py-4">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
          <p class="text-white-50 mb-1">Welcome back</p>
          <h2 class="text-white mb-0">{{ auth.user?.name }}</h2>
        </div>
        <form class="d-flex flex-wrap gap-2" @submit.prevent="handleCreate">
          <input v-model="form.name" placeholder="New pet name" class="form-control" />
          <select v-model="form.species" class="form-select">
            <option v-for="option in speciesOptions" :key="option" :value="option">
              {{ option }}
            </option>
          </select>
          <button class="btn btn-info">
            Create
          </button>
        </form>
      </div>

      <div v-if="petsStore.error" class="alert alert-danger">
        {{ petsStore.error }}
      </div>

      <div class="row g-4">
        <div v-for="pet in petsStore.list" :key="pet.id" class="col-12 col-md-6 col-lg-4">
          <PetCard :pet="pet" :is-active="pet.id === petsStore.activePetId" @select="petsStore.selectPet" />
        </div>
      </div>

      <div v-if="!petsStore.list.length && !petsStore.isLoading" class="text-center text-white-50 mt-5">
        No pets yet. Create your first companion above.
      </div>
    </div>
  </section>
</template>
