<template>
  <button
    @click="toggle"
    :disabled="loading"
    class="px-4 py-2 rounded font-bold text-white transition"
    :class="isReady ? 'bg-green-600 hover:bg-green-500' : 'bg-yellow-500 hover:bg-yellow-400'"
  >
    {{ loading ? '...' : (isReady ? '✅ Prêt' : '⏳ Je suis prêt') }}
  </button>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { toggleReady } from '@/services/roomUserService'

const props = defineProps<{
  roomUserId: number
  initialReady: boolean
}>()

const emit = defineEmits(['updated'])

const isReady = ref(props.initialReady)
const loading = ref(false)
const token = localStorage.getItem('jwt_token') || ''

const toggle = async () => {
  if (!token) return alert('Non connecté')
  loading.value = true
  const result = await toggleReady(props.roomUserId, !isReady.value, token)
  if (result.ok) {
    isReady.value = !isReady.value
    // ➕ Pause courte pour laisser le backend mettre à jour les données
    setTimeout(() => emit('updated'), 300)
  } else {
    alert('❌ Erreur lors du changement de statut')
  }
  loading.value = false
}
</script>
