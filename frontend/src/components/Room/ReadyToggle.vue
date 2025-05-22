<template>
    <div class="text-center mt-4">
      <button
        @click="toggleReady"
        :class="ready ? 'bg-green-500' : 'bg-yellow-400'"
        class="w-full py-2 text-white font-bold rounded transition"
      >
        {{ ready ? '✅ Prêt' : '❌ Pas prêt' }}
      </button>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  
  const ready = ref(false)
  
  function toggleReady() {
    ready.value = !ready.value
  }
  </script>
  