<template>
<div class="flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl mx-auto border-2 border-blue-300 rounded-xl shadow-md p-8 bg-white">
        <h1 class="text-4xl text-blue-700 font-extrabold uppercase tracking-widest mb-6 text-center">
          Bienvenue sur Roomies
        </h1>
  
        <p class="text-lg text-gray-800 mb-4">
          🎮 <strong>Roomies</strong>, c'est bien plus qu'un simple site de jeux. C'est une expérience communautaire
          où tu peux jouer en ligne avec d'autres joueurs **en temps réel**, tout en discutant via un **chat intégré** 🔥.
        </p>
  
        <ul class="list-disc list-inside space-y-3 text-gray-700 text-base">
          <li><span class="font-semibold text-blue-800">Crée ou rejoins des rooms</span> en un clic</li>
          <li><span class="font-semibold text-blue-800">Chat en direct</span> avec tous les joueurs présents dans ta room</li>
          <li><span class="font-semibold text-blue-800">Joue à des mini-jeux</span> (cartes, roulette, paris, etc.) entre amis ou inconnus</li>
          <li><span class="font-semibold text-blue-800">Gagne des coins</span> pour améliorer ton profil</li>
          <li><span class="font-semibold text-blue-800">Classement et avatars personnalisés</span> pour une ambiance unique</li>
        </ul>
  
        <div v-if="isConnected" class="mt-8">
          <form @submit.prevent="createRoom" class="mb-4 p-4 border rounded bg-gray-50">
            <div class="mb-2">
              <label class="block text-sm font-bold mb-1">Nom de la room</label>
              <input v-model="roomName" class="border p-1 rounded w-full" required />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-bold mb-1">Nombre de joueurs max</label>
              <input v-model.number="maxPlayers" type="number" min="2" max="20" class="border p-1 rounded w-full" required />
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Créer la room</button>
            <div v-if="result" class="mt-2" :class="result.success ? 'text-green-600' : 'text-red-600'">
              {{ result.message }}
            </div>
          </form>
        </div>
        <div v-else class="mt-8 text-center text-blue-700 font-semibold">
          Connecte-toi pour créer une room !
        </div>
        <div class="mt-8 flex flex-col sm:flex-row sm:justify-center gap-4">
          <RouterLink
            to="/register"
            class="inline-block px-6 py-3 bg-yellow-400 text-blue-900 font-bold text-center border border-yellow-500 rounded hover:bg-yellow-300 transition"
          >
            ✨ Créer un compte
          </RouterLink>
          <RouterLink
            to="/login"
            class="inline-block px-6 py-3 bg-blue-600 text-white font-bold text-center border border-blue-700 rounded hover:bg-blue-500 transition"
          >
            🔐 Se connecter
          </RouterLink>
        </div>
      </div>
    </div>
  </template>
  
<script setup lang="ts">
import { ref, computed } from 'vue'

const roomName = ref('')
const maxPlayers = ref(4)
const result = ref<{ success: boolean; message: string } | null>(null)
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
const token = ref(localStorage.getItem('jwt_token') || '')
const isConnected = computed(() => !!token.value)

async function createRoom() {
  result.value = null
  if (!token.value) {
    result.value = { success: false, message: 'Vous devez être connecté.' }
    return
  }
  if (!roomName.value.trim()) {
    result.value = { success: false, message: 'Le nom de la room est obligatoire.' }
    return
  }
  console.log('Room name:', roomName.value, 'Max players:', maxPlayers.value);
  try {
    const res = await fetch(`${API_URL}/rooms`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/ld+json',
        'Authorization': `Bearer ${token.value}`,
      },
      body: JSON.stringify({ name: roomName.value, maxPlayers: maxPlayers.value }),
    })
    const data = await res.json()
    if (res.ok) {
      result.value = { success: true, message: 'Room créée ! 🎉' }
      roomName.value = ''
      maxPlayers.value = 4
    } else {
      result.value = { success: false, message: data.error || JSON.stringify(data) }
    }
  } catch (e: any) {
    result.value = { success: false, message: e.message }
  }
}
</script>

<style scoped>
form input {
  margin-bottom: 0.5rem;
}
</style>
  