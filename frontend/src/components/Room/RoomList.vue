<template>
    <section class="w-full max-w-4xl border border-gray-200 rounded-xl shadow p-6 bg-white">
      <h2 class="text-2xl font-bold text-blue-700 mb-4 text-center">📋 Rooms disponibles</h2>
      <div v-if="loading">Chargement...</div>
      <div v-else-if="error" class="text-red-600 text-center">{{ error }}</div>
      <div v-else-if="rooms.length === 0" class="text-gray-600 text-center">Aucune room disponible.</div>
  
      <ul class="space-y-4" v-else>
        <li v-for="room in rooms" :key="room['@id']">
          <RoomCard :room="room" />
        </li>
      </ul>
    </section>
  </template>
  
  <script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { fetchRooms } from '@/services/roomService'
  import RoomCard from './RoomCard.vue'
  
  const rooms = ref([])
  const loading = ref(true)
  const error = ref('')
  
  onMounted(async () => {
    try {
      rooms.value = await fetchRooms()
    } catch (e: any) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  })
  </script>
  