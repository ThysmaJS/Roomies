<template>
    <div v-if="room" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded shadow-lg max-w-md w-full relative">
        <button @click="close" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">✖</button>
        <h2 class="text-2xl font-bold text-blue-700 mb-4">{{ room.name }}</h2>
        <p class="text-gray-700 mb-2">👥 Max joueurs : {{ room.maxPlayers }}</p>
        <p class="text-gray-700 mb-2">🕓 Créée le : {{ formatDate(room.createdAt) }}</p>
        <p class="text-gray-700 mb-2">🎮 Jeu : {{ room.currentGame?.name || 'Non défini' }}</p>
        <p class="text-gray-700 mb-4">👤 Créateur : {{ room.owner?.email || 'Inconnu' }}</p>
  
        <RouterLink
  :to="`/room/${room.id}`"
  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition inline-block text-center mt-2"
>
  🔗 Rejoindre cette room
</RouterLink>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps<{ room: any }>()
  const emit = defineEmits(['close'])
  
  function close() {
    emit('close')
  }
  
  function formatDate(date: string) {
    return new Date(date).toLocaleString()
  }
  </script>
  