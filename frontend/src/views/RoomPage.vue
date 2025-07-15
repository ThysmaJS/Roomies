<template>
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 h-screen">
    <!-- Zone du jeu -->
    <div class="col-span-3 bg-white p-6 overflow-auto">
      <div v-if="room">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">{{ room.name }}</h2>
        <GameContainer :game="room.currentGame" />

        <!-- Liste des joueurs -->
        <div class="mt-6">
          <h3 class="text-lg font-bold text-blue-700 mb-2">👥 Joueurs dans la room</h3>
          <ul class="space-y-2 text-sm text-blue-900">
            <li v-for="ru in room.roomUsers" :key="ru.id" class="flex items-center justify-between gap-4 p-2 bg-gray-50 border rounded">
              <div>
                <span>👤 {{ ru.user?.username || ru.user?.email || 'Inconnu' }}</span>
                <span v-if="ru.isReady" class="text-green-600 font-bold ml-2">✅ Prêt</span>
                <span v-else class="text-yellow-500 ml-2">⏳ En attente</span>
              </div>
              <ReadyToggle
                v-if="ru.user?.email === currentUserEmail"
                :roomUserId="ru.id"
                :initialReady="ru.isReady"
                @updated="loadRoom"
              />
            </li>
          </ul>
        </div>
      </div>
      <div v-else class="text-center text-gray-600">Chargement de la room...</div>
    </div>

    <!-- Zone latérale : Chat -->
    <div class="col-span-1 bg-gray-100 p-4 flex flex-col justify-between border-l border-gray-300">
      <RoomChat :roomId="roomId" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import GameContainer from '@/components/Room/GameContainer.vue'
import RoomChat from '@/components/Room/RoomChat.vue'
import ReadyToggle from '@/components/Room/ReadyToggle.vue'
import { jwtDecode } from 'jwt-decode'
import { cloneDeep } from 'lodash'

const route = useRoute()
const rawParam = route.params.id
const roomId = Array.isArray(rawParam) ? rawParam[0] : rawParam
const room = ref<any>(null)
const token = localStorage.getItem('jwt_token')
const currentUserEmail = token ? jwtDecode<any>(token).username : ''

let interval: number | undefined

async function loadRoom() {
  try {
    const res = await axios.get(`/api/rooms/${roomId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    // 🔁 Clonage profond pour forcer la réactivité
    room.value = cloneDeep(res.data)
  } catch (err) {
    console.error('Erreur lors du chargement de la room', err)
  }
}

onMounted(() => {
  loadRoom()
  interval = window.setInterval(loadRoom, 5000)
})

onUnmounted(() => {
  clearInterval(interval)
})
</script>
