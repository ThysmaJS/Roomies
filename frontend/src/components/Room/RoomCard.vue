<template>
    <div class="p-4 border rounded bg-gray-50 shadow">
      <h3 class="text-xl font-semibold text-blue-800">{{ room.name }}</h3>
      <p class="text-sm text-gray-600">
        ID : {{ room.id }}<br />
        Max joueurs : {{ room.maxPlayers }}<br />
        Créée le : {{ formatDate(room.createdAt) }}
      </p>
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps<{ room: any }>()
  
  function formatDate(date: string) {
    return new Date(date).toLocaleString()
  }
  </script>
  