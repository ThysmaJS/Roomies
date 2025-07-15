<template>
  <section class="border border-gray-300 rounded-xl bg-white p-6 shadow">
    <h2 class="text-xl font-semibold text-blue-700 mb-4">📋 Liste des rooms</h2>

    <div v-if="loading">Chargement des rooms...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>
    <div v-else-if="filteredRooms.length === 0" class="text-gray-600">Aucune room disponible.</div>

    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" v-else>
      <li v-for="room in filteredRooms" :key="room.id">
        <RoomCard :room="room" @select="selectRoom" />
      </li>
    </ul>

    <RoomDetailModal v-if="selectedRoom" :room="selectedRoom" @close="selectedRoom = null" />
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { fetchRooms } from '@/services/roomService'
import RoomCard from './RoomCard.vue'
import RoomDetailModal from './RoomDetailModal.vue'
import { useAuthStore } from '@/stores/auth'

// Interfaces
interface User {
  id: number
  email: string
  username: string
}

interface RoomUser {
  id: number
  user: User
  isReady: boolean
}

interface Room {
  id: number
  name: string
  maxPlayers: number
  createdAt?: string
  owner?: User
  roomUsers: RoomUser[]
  currentGame?: string // ← ajoute cette ligne
}

// States
const loading = ref(true)
const error = ref('')
const rooms = ref<Room[]>([])
const selectedRoom = ref<Room | null>(null)

// Auth
const auth = useAuthStore()
const userId = auth.userId

defineExpose({ loadRooms })

// Charger les rooms
async function loadRooms() {
  loading.value = true
  try {
    const token = localStorage.getItem('jwt_token') || ''
    rooms.value = await fetchRooms(token)
  } catch (e: any) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

// Filtrage : ne pas afficher les rooms déjà rejointes ou créées
const filteredRooms = computed(() =>
  rooms.value.filter((room) => {
    const joined = room.roomUsers?.some((ru) => ru.user?.id === userId)
    const isOwner = room.owner?.id === userId
    return !joined && !isOwner
  })
)

onMounted(loadRooms)

function selectRoom(room: Room) {
  selectedRoom.value = room
}
</script>
