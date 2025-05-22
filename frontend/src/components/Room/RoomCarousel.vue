<template>
  <section class="w-full max-w-6xl mx-auto p-6 bg-white border-2 border-cyan-300 rounded-xl shadow overflow-hidden">
    <h2 class="text-2xl font-bold text-blue-700 mb-6 text-center">📋 Rooms disponibles</h2>

    <div class="relative overflow-hidden">
      <div class="flex w-max animate-scroll space-x-6">
        <!-- Double boucle pour créer un effet infini -->
        <template v-for="n in 2" :key="n">
          <div
            v-for="(room, i) in rooms"
            :key="`${n}-${i}`"
            class="min-w-[280px] flex-shrink-0"
          >
            <RoomCard :room="room" />
          </div>
        </template>
      </div>
    </div>

    <div class="mt-6 text-center">
      <RouterLink
        to="/rooms"
        class="inline-block px-6 py-3 bg-cyan-500 text-white font-bold rounded hover:bg-cyan-400 shadow transition"
      >
        🔍 Afficher toutes les rooms
      </RouterLink>
    </div>
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { fetchRooms } from '@/services/roomService'
import RoomCard from './RoomCard.vue'

const rooms = ref([])

onMounted(async () => {
  try {
    rooms.value = await fetchRooms()
  } catch (e) {
    console.error(e)
  }
})
</script>

<style scoped>
@keyframes scroll {
  0% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(-50%);
  }
}

.animate-scroll {
  animation: scroll 30s linear infinite;
}
</style>
