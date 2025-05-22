<template>
    <section class="border border-gray-300 rounded-xl bg-white p-6 shadow">
      <h2 class="text-xl font-semibold text-blue-700 mb-4">📋 Liste des rooms</h2>
      <div v-if="loading">Chargement des rooms...</div>
      <div v-else-if="error" class="text-red-600">{{ error }}</div>
      <div v-else-if="rooms.length === 0" class="text-gray-600">Aucune room pour le moment.</div>
  
      <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" v-else>
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
  
  onMounted(loadRooms)
  defineExpose({ loadRooms })
  
  async function loadRooms() {
    loading.value = true
    try {
      rooms.value = await fetchRooms()
    } catch (e: any) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }
  </script>
  