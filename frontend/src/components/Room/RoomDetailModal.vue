<template>
    <div v-if="room" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded shadow-lg max-w-md w-full relative">
        <button @click="emit('close')" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">✖</button>
        <h2 class="text-2xl font-bold text-blue-700 mb-4">{{ room.name }}</h2>
        <p class="text-gray-700 mb-2">👥 Max joueurs : {{ room.maxPlayers }}</p>
        <p class="text-gray-700 mb-2">🕓 Créée le : {{ formatDate(room.createdAt) }}</p>
        <p class="text-gray-700 mb-2">🎮 Jeu : {{ room.currentGame?.name || 'Non défini' }}</p>
        <p class="text-gray-700 mb-4">👤 Créateur : {{ room.owner?.email || 'Inconnu' }}</p>
  
        <button
          @click="handleJoin"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition inline-block text-center mt-2 w-full"
        >
          🔗 Rejoindre cette room
        </button>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useRouter } from 'vue-router'
  import { joinRoom } from '@/services/roomService'
  
  const props = defineProps<{ room: any }>()
  const emit = defineEmits(['close'])
  
  const router = useRouter()
  const token = localStorage.getItem('jwt_token')
  
  async function handleJoin() {
    if (!token) {
      router.push('/login')
      return
    }
  
    const result = await joinRoom(props.room.id, token)
    if (result.ok) {
      emit('close') // ferme la modal
      router.push(`/room/${props.room.id}`)
    } else {
      alert('❌ Erreur lors de la tentative de rejoindre la room')
    }
  }
  
  function formatDate(date: string) {
    return new Date(date).toLocaleString()
  }
  </script>
  