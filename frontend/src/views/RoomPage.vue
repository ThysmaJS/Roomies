<template>
    <div class="grid grid-cols-1 lg:grid-cols-4 h-screen">
      <!-- Zone du jeu -->
      <div class="col-span-3 bg-white p-6 overflow-auto">
        <div v-if="room">
          <h2 class="text-2xl font-bold text-blue-700 mb-4">{{ room.name }}</h2>
          <GameContainer :game="room.currentGame" />
  
          <!-- Liste des joueurs -->
          <div class="mt-6">
            <h3 class="text-lg font-bold text-blue-700 mb-2">👥 Joueurs dans la room</h3>
            <ul class="space-y-1 text-sm text-blue-900">
              <li
                v-for="ru in room.roomUsers"
                :key="ru.user?.email"
                class="flex items-center gap-2"
              >
                <span>👤 {{ ru.user?.username || ru.user?.email || 'Inconnu' }}</span>
                <span v-if="ru.isReady" class="text-green-600 font-bold">✅ Prêt</span>
                <span v-else class="text-yellow-500">⏳ En attente</span>
              </li>
            </ul>
          </div>
        </div>
        <div v-else class="text-center text-gray-600">Chargement de la room...</div>
      </div>
  
      <!-- Zone latérale : Chat + Prêt -->
      <div class="col-span-1 bg-gray-100 p-4 flex flex-col justify-between border-l border-gray-300">
        <RoomChat :roomId="roomId" />
        <ReadyToggle />
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import axios from 'axios'
  import GameContainer from '@/components/Room/GameContainer.vue'
  import RoomChat from '@/components/Room/RoomChat.vue'
  import ReadyToggle from '@/components/Room/ReadyToggle.vue'
  
  const route = useRoute()
  const room = ref<any>(null)
  const rawParam = route.params.id
  const roomId = Array.isArray(rawParam) ? rawParam[0] : rawParam

  
  onMounted(async () => {
    const res = await axios.get(`/api/rooms/${roomId}`)
    room.value = res.data
  })
  </script>
  