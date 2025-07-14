<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { joinRoom } from '@/services/roomService'
import { useAuthStore } from '@/stores/auth'

// props & emits
const props = defineProps<{ room: any }>()
const emit = defineEmits(['close'])

// router & auth
const router = useRouter()
const auth = useAuthStore()
const token = auth.token
const userId = auth.userId

// ✅ Vérifie si l'utilisateur est le créateur ou déjà dans les roomUsers
const alreadyJoined = computed(() => {
  return (
    props.room?.owner?.id === userId ||
    props.room?.roomUsers?.some((ru: any) => ru.user?.id === userId)
  )
})

// ✅ Action à effectuer selon l'état
async function handleJoinOrView() {
  if (!token) {
    return router.push('/login')
  }

  if (!alreadyJoined.value) {
    const result = await joinRoom(props.room.id, token)
    if (!result.ok) {
      alert('❌ Erreur lors de la tentative de rejoindre la room')
      return
    }
  }

  emit('close')
  router.push(`/room/${props.room.id}`)
}

// ✅ Format de date
function formatDate(date: string) {
  return new Date(date).toLocaleString()
}
</script>

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
        @click="handleJoinOrView"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition inline-block text-center mt-2 w-full"
      >
        {{ alreadyJoined ? '👁 Voir la room' : '🔗 Rejoindre cette room' }}
      </button>
    </div>
  </div>
</template>
