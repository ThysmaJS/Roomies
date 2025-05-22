<template>
    <div class="flex flex-col h-full">
      <div class="flex-1 overflow-y-auto p-2 bg-white border rounded shadow mb-2">
        <div v-for="(message, index) in messages" :key="index" class="text-sm text-gray-800 mb-1">
          <strong>{{ message.user }} :</strong> {{ message.text }}
        </div>
      </div>
  
      <form @submit.prevent="sendMessage" class="flex gap-2">
        <input
          v-model="newMessage"
          placeholder="Écris ton message..."
          class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm"
        />
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-500">
          Envoyer
        </button>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  
  const props = defineProps<{ roomId: string | number }>()
  
  const messages = ref([
    { user: 'Alice', text: 'Salut tout le monde !' },
    { user: 'Bob', text: 'Prêt pour jouer !' },
  ])
  
  const newMessage = ref('')
  
  function sendMessage() {
    if (!newMessage.value.trim()) return
    messages.value.push({ user: 'Moi', text: newMessage.value })
    newMessage.value = ''
  }
  </script>
  