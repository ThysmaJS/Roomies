<template>
  <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-[350px]">
      <h2 class="text-xl font-bold mb-4 text-blue-700">Modifier la room</h2>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="block text-sm mb-1 font-semibold">Nom</label>
          <input v-model="form.name" class="border px-2 py-1 rounded w-full" required maxlength="50" />
        </div>
        <div class="mb-3">
          <label class="block text-sm mb-1 font-semibold">Nombre de joueurs</label>
          <input v-model.number="form.maxPlayers" type="number" min="2" max="10" class="border px-2 py-1 rounded w-full" required />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button type="button" @click="close" class="px-4 py-1 bg-gray-200 rounded">Annuler</button>
          <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineEmits, defineProps } from 'vue'

const props = defineProps<{
  visible: boolean
  room: any | null
}>()
const emits = defineEmits(['close', 'saved'])

const form = ref({ name: '', maxPlayers: 2 })

watch(
  () => props.room,
  (room) => {
    if (room) form.value = { name: room.name, maxPlayers: room.maxPlayers }
  },
  { immediate: true }
)

function close() {
  emits('close')
}

function submit() {
  emits('saved', { ...form.value })
}
</script>
