<template>
  <div class="chat-box">
    <div v-if="currentRoom" class="chat-header">
      <h2>{{ currentRoom.name }}</h2>
      <p>{{ currentRoom.description }}</p>
    </div>
    
    <div class="messages" ref="messagesContainer">
      <div v-for="message in messages" :key="message.id" class="message">
        <div class="message-header">
          <span class="username">{{ message.user.username }}</span>
          <span class="timestamp">{{ formatDate(message.createdAt) }}</span>
        </div>
        <div class="message-content">{{ message.content }}</div>
      </div>
    </div>
    
    <div class="message-input">
      <input
        v-model="newMessage"
        @keyup.enter="sendMessage"
        placeholder="Type your message..."
      />
      <button @click="sendMessage" :disabled="!newMessage.trim()">
        Send
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/store/auth';
import { useRoomStore } from '@/store/room';
import { socketService } from '@/sockets/socket';
import type { Message } from '@/types/Message';

const route = useRoute();
const authStore = useAuthStore();
const roomStore = useRoomStore();

const messages = ref<Message[]>([]);
const newMessage = ref('');
const messagesContainer = ref<HTMLElement | null>(null);

const currentRoom = computed(() => roomStore.currentRoom);

const loadMessages = async () => {
  if (!currentRoom.value) return;
  // TODO: Load messages from API
};

const sendMessage = () => {
  if (!newMessage.value.trim() || !currentRoom.value || !authStore.user) return;

  const message: Message = {
    id: Date.now().toString(),
    content: newMessage.value,
    roomId: currentRoom.value.id,
    user: {
      id: authStore.user.id,
      username: authStore.user.username,
      email: authStore.user.email,
      avatar: authStore.user.avatar,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    },
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  };

  messages.value.push(message);
  socketService.sendMessage(currentRoom.value.id, newMessage.value);
  newMessage.value = '';
  scrollToBottom();
};

const handleNewMessage = (message: Message) => {
  messages.value.push(message);
  scrollToBottom();
};

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleTimeString();
};

// Watch for room changes
watch(() => route.params.id, (newRoomId) => {
  if (newRoomId) {
    loadMessages();
  }
}, { immediate: true });

// Setup socket listeners
onMounted(() => {
  loadMessages();
  socketService.onMessageReceived(handleNewMessage);
});

// Clean up socket listeners
onUnmounted(() => {
  socketService.off('message', handleNewMessage);
  if (currentRoom.value) {
    socketService.leaveRoom(currentRoom.value.id);
  }
});
</script>

<style scoped>
.chat-box {
  display: flex;
  flex-direction: column;
  height: 100%;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
}

.chat-header {
  padding: 1rem;
  background-color: #f5f5f5;
  border-bottom: 1px solid #e0e0e0;
}

.messages {
  flex: 1;
  overflow-y: auto;
  padding: 1rem;
}

.message {
  margin-bottom: 1rem;
  padding: 0.5rem;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.message-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.25rem;
  font-size: 0.875rem;
  color: #666;
}

.message-content {
  word-break: break-word;
}

.message-input {
  display: flex;
  padding: 1rem;
  border-top: 1px solid #e0e0e0;
  background-color: #fff;
}

.message-input input {
  flex: 1;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-right: 0.5rem;
}

.message-input button {
  padding: 0.5rem 1rem;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.message-input button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
