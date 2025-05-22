<template>
    <div class="w-full h-full flex items-center justify-center border border-dashed border-blue-300 rounded bg-blue-50 text-blue-700">
      <p class="text-xl font-bold text-center">
        🎮 Jeu en cours : {{ game?.name || 'Aucun jeu sélectionné' }}
      </p>
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps<{ game: any }>()
  </script>
  