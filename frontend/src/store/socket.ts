import { defineStore } from 'pinia';
import { ref } from 'vue';

type AppSocket = {
  disconnect: () => void;
  on: (event: string, callback: (...args: any[]) => void) => void;
  off: (event: string, callback?: (...args: any[]) => void) => void;
};

export const useSocketStore = defineStore('socket', () => {
  const socket = ref<AppSocket | null>(null);
  const isConnected = ref(false);
  const error = ref<string | null>(null);

  function setSocket(socketInstance: AppSocket) {
    if (socket.value) {
      socket.value.disconnect();
    }
    
    socket.value = socketInstance;
    isConnected.value = true;
    
    socketInstance.on('disconnect', () => {
      isConnected.value = false;
    });
    
    socketInstance.on('error', (err: Error) => {
      error.value = err.message;
      console.error('Socket error:', err);
    });
  }

  function disconnect() {
    if (socket.value) {
      socket.value.disconnect();
      socket.value = null;
      isConnected.value = false;
    }
  }

  return {
    socket,
    isConnected,
    error,
    setSocket,
    disconnect
  };
});
