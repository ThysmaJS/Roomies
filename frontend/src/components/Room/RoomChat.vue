<template>
  <div class="flex flex-col h-full">
    <h3 class="text-lg font-bold text-blue-700 mb-2">💬 Chat</h3>

    <div class="flex-1 overflow-y-auto bg-white border rounded p-2 mb-2">
      <div v-for="(msg, index) in messages" :key="index" class="mb-1">
        <strong>{{ msg.user }}:</strong> {{ msg.text }}
      </div>
    </div>

    <div class="flex gap-2">
      <input
        v-model="newMessage"
        @keyup.enter="sendMessage"
        placeholder="Écris ton message..."
        class="flex-1 px-2 py-1 border rounded"
      />
      <button @click="sendMessage" class="px-4 py-1 bg-blue-600 text-white rounded">Envoyer</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { jwtDecode } from 'jwt-decode'

const props = defineProps<{
  roomId: string
}>()

const newMessage = ref('')
const messages = ref<{ user: string; text: string }[]>([])
let socket: WebSocket | null = null

const token = localStorage.getItem('jwt_token') || ''
const username = token ? jwtDecode<any>(token).username : 'Invité'

function connectWebSocket() {
  socket = new WebSocket('ws://localhost:3001')

  socket.addEventListener('open', () => {
    console.log('✅ WebSocket connecté')
    socket?.send(JSON.stringify({ type: 'join', room: props.roomId }))
  })

  socket.addEventListener('message', (event) => {
    try {
      if (!event.data || typeof event.data !== 'string') {
        console.warn('🟡 Donnée WebSocket vide ou non textuelle :', event.data)
        return
      }

      const data = JSON.parse(event.data)

      if (data.type === 'message') {
        messages.value.push({ user: data.user, text: data.text })
      } else {
        console.log('🔵 Message ignoré (type inconnu) :', data)
      }
    } catch (err) {
      console.error('❌ Erreur de parsing WebSocket', err, event.data)
    }
  })
}


function sendMessage() {
  if (!newMessage.value.trim() || !socket || socket.readyState !== WebSocket.OPEN) return

  const message = {
    type: 'message',
    room: props.roomId,
    user: username,
    text: newMessage.value.trim()
  }

  socket.send(JSON.stringify(message))
  newMessage.value = ''
}

onMounted(() => {
  connectWebSocket()
})

onBeforeUnmount(() => {
  socket?.close()
})
</script>
