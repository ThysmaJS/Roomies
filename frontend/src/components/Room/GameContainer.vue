<template>
  <div class="flex flex-col items-center gap-4">
    <h2 class="text-xl font-bold text-blue-700">🎮 Morpion</h2>
    <div class="mb-2">
      <span v-if="symbol" class="px-2 py-1 bg-blue-100 rounded text-blue-800">Tu joues : {{ symbol }}</span>
      <span v-else class="px-2 py-1 bg-gray-200 rounded">Spectateur</span>
    </div>
    <div class="mb-1 text-base" v-if="currentPlayer">
      <span v-if="winner" class="text-green-600 font-bold">{{ winnerMessage }}</span>
      <span v-else>
        <span v-if="currentPlayer === symbol">À toi de jouer !</span>
        <span v-else>En attente du joueur {{ currentPlayer }}</span>
      </span>
    </div>
    <div class="grid grid-cols-3 gap-2">
      <div
        v-for="(cell, index) in board"
        :key="index"
        @click="play(index)"
        class="w-16 h-16 flex items-center justify-center border text-2xl font-bold bg-white hover:bg-gray-100 cursor-pointer"
        :class="{ 'bg-gray-300 cursor-not-allowed': !!cell || winner || currentPlayer !== symbol }"
      >
        {{ cell }}
      </div>
    </div>
    <button v-if="winner" @click="reset" class="mt-4 px-4 py-1 rounded bg-blue-600 text-white font-bold">Rejouer</button>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { jwtDecode } from 'jwt-decode'

const props = defineProps<{ roomId: string }>()

const board = ref<string[]>(Array(9).fill(''))
const currentPlayer = ref<'X' | 'O' | null>(null)
const winner = ref<string | null>(null)
const symbol = ref<'X' | 'O' | null>(null)

let socket: WebSocket | null = null

const token = localStorage.getItem('jwt_token') || ''
const username = token ? jwtDecode<any>(token).username : 'Invité'

onMounted(() => {
  socket = new WebSocket('ws://localhost:3001')
  socket.addEventListener('open', () => {
    socket?.send(JSON.stringify({ type: 'join', room: props.roomId, user: username }))
  })
  socket.addEventListener('message', (event) => {
    try {
      const data = JSON.parse(event.data)
      if (data.type === 'symbol') {
        symbol.value = data.symbol
      }
      if (data.type === 'board') {
        board.value = data.board
        currentPlayer.value = data.currentPlayer
        winner.value = data.winner
      }
    } catch (err) {
      console.error('Erreur parsing WebSocket', err)
    }
  })
})

onBeforeUnmount(() => {
  socket?.close()
})

function play(index: number) {
  if (!symbol.value) return // spectateur
  if (winner.value) return // partie finie
  if (board.value[index]) return // case déjà prise
  if (currentPlayer.value !== symbol.value) return // pas notre tour

  socket?.send(JSON.stringify({
    type: 'play',
    room: props.roomId,
    user: username,
    index,
    symbol: symbol.value
  }))
}

const winnerMessage = computed(() =>
  winner.value ? `🎉 ${winner.value} a gagné !` : ''
)

function reset() {
  // Simple "reset": reconnecte au WS (reset serveur auto après tout le monde part)
  window.location.reload()
}
</script>
