<template>
  <div class="flex flex-col items-center gap-4">
    <h2 class="text-xl font-bold text-blue-700">🎮 Morpion</h2>
    <div class="grid grid-cols-3 gap-2">
      <div v-for="(cell, index) in board" :key="index" @click="play(index)"
           class="w-16 h-16 flex items-center justify-center border text-2xl font-bold bg-white hover:bg-gray-100 cursor-pointer">
        {{ cell }}
      </div>
    </div>
    <p v-if="winner" class="text-green-600 font-bold text-lg">{{ winnerMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
const props = defineProps<{ roomId: string }>()

const board = ref(Array(9).fill(''))
const currentPlayer = ref<'X' | 'O'>('X')
const winner = ref<string | null>(null)

function play(index: number) {
  if (board.value[index] || winner.value) return
  board.value[index] = currentPlayer.value
  checkWinner()
  currentPlayer.value = currentPlayer.value === 'X' ? 'O' : 'X'
}

function checkWinner() {
  const wins = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8],
    [0, 3, 6], [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
  ]
  for (const [a, b, c] of wins) {
    if (board.value[a] && board.value[a] === board.value[b] && board.value[a] === board.value[c]) {
      winner.value = board.value[a]
      return
    }
  }
}

const winnerMessage = computed(() => winner.value ? `🎉 ${winner.value} a gagné !` : '')
</script>
