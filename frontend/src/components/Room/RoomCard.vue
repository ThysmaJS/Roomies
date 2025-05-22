<template>
  <div
    class="border border-cyan-200 rounded-lg bg-cyan-50 p-4 shadow cursor-pointer hover:shadow-md transition"
    @click="$emit('select', room)"
  >
    <h3 class="text-lg font-bold text-blue-800">{{ room.name }}</h3>
    <p class="text-sm text-gray-700">Max joueurs : {{ room.maxPlayers }}</p>
    <p class="text-xs text-gray-500">Créée le : {{ formatDate(room.createdAt) }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{ room: any }>()

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleString()
}
</script>
