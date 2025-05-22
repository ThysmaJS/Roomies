<template>
    <section class="w-full max-w-2xl border border-gray-300 rounded-xl shadow p-6 bg-gray-50">
      <h2 class="text-2xl font-bold text-blue-700 mb-4 text-center">🎯 Créer une room</h2>
  
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-bold mb-1">Nom de la room</label>
          <input v-model="name" class="border p-2 rounded w-full" required />
        </div>
        <div>
          <label class="block text-sm font-bold mb-1">Nombre de joueurs max</label>
          <input v-model.number="maxPlayers" type="number" min="2" max="20" class="border p-2 rounded w-full" required />
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition">
          Créer la room
        </button>
        <div v-if="message" :class="success ? 'text-green-600' : 'text-red-600'">{{ message }}</div>
      </form>
    </section>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { createRoom } from '@/services/roomService'
  
  const name = ref('')
  const maxPlayers = ref(4)
  const message = ref('')
  const success = ref(false)
  
  const router = useRouter()
  const token = localStorage.getItem('jwt_token') || ''
  
  async function handleSubmit() {
    if (!token) {
      router.push('/login')
      return
    }
  
    const result = await createRoom(token, name.value, maxPlayers.value)
    success.value = result.ok
    message.value = result.ok ? 'Room créée ! 🎉' : result.data.error || 'Erreur'
    if (result.ok) {
      name.value = ''
      maxPlayers.value = 4
    }
  }
  </script>
  