<template>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">📁 Mes Rooms créées</h1>
  
      <div v-if="rooms.length">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <RoomCard
            v-for="room in rooms"
            :key="room.id"
            :room="room"
            @click="() => $router.push(`/room/${room.id}`)"
          />
        </div>
      </div>
  
      <div v-else class="text-center text-gray-500 mt-8">
        😢 Aucune room créée pour l’instant.
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { fetchMyRooms } from '@/services/roomService'
  import RoomCard from '@/components/Room/RoomCard.vue'
  
  const router = useRouter()
  const rooms = ref<any[]>([])
  const token = localStorage.getItem('jwt_token')
  
  onMounted(async () => {
    if (!token) return router.push('/login')
  
    try {
      rooms.value = await fetchMyRooms(token)
    } catch (err) {
      console.error('Erreur lors du chargement de mes rooms', err)
    }
  })
  </script>
  