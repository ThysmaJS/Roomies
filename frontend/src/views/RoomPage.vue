<template>
    <div class="grid grid-cols-1 lg:grid-cols-4 h-screen">
      <!-- Jeu -->
      <div class="col-span-3 bg-white p-6 overflow-auto">
        <GameContainer :game="room?.currentGame" />
      </div>
  
      <!-- Chat + Bouton prêt -->
      <div class="col-span-1 bg-gray-100 p-4 flex flex-col justify-between border-l border-gray-300">
        <RoomChat :roomId="roomId" />
        <ReadyToggle />
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import GameContainer from '@/components/Room/GameContainer.vue'
  import RoomChat from '@/components/Room/RoomChat.vue'
  import ReadyToggle from '@/components/Room/ReadyToggle.vue'
  import axios from 'axios'
  
  const route = useRoute()
  const roomId = route.params.id
  const room = ref(null)
  
  onMounted(async () => {
    const res = await axios.get(`/api/rooms/${roomId}`)
    room.value = res.data
  })
  </script>
  