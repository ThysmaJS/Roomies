<template>
  <div
    class="border border-cyan-200 rounded-lg bg-cyan-50 p-4 shadow cursor-pointer hover:shadow-md transition"
    @click="$emit('select', room)"
  >
    <h3 class="text-lg font-bold text-blue-800">{{ room.name }}</h3>
    <p class="text-sm text-gray-700">Max joueurs : {{ room.maxPlayers }}</p>
    <p class="text-xs text-gray-500">Créée le : {{ formatDate(room.createdAt) }}</p>

    <div class="mt-2">
      <p class="text-sm font-semibold text-blue-600">👥 Joueurs :</p>
      <ul class="text-sm text-gray-800">
        <li
          v-for="ru in room.roomUsers"
          :key="ru.id"
          class="flex items-center gap-2"
        >
          {{ ru.user?.username || ru.user?.email || 'Inconnu' }}
          <span v-if="ru.isReady" class="text-green-600">✅ Prêt</span>
          <span v-else class="text-yellow-600">⏳ En attente</span>
        </li>
      </ul>
    </div>

    <!-- Actions créateur -->
    <div v-if="showActions" class="mt-4 flex gap-2">
      <button @click.stop="$emit('edit')" class="bg-yellow-400 px-2 py-1 rounded font-bold text-white hover:bg-yellow-500">Modifier</button>
      <button @click.stop="$emit('delete')" class="bg-red-600 px-2 py-1 rounded font-bold text-white hover:bg-red-700">Supprimer</button>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ room: any, showActions?: boolean }>()
function formatDate(dateString: string) {
  return new Date(dateString).toLocaleString()
}
</script>
