<template>
  <div class="space-y-8">
    <!-- Header with Create Room Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Salles disponibles</h2>
        <p class="mt-1 text-sm text-gray-500">Rejoignez une salle existante ou créez-en une nouvelle</p>
      </div>
      <button 
        @click="showCreateRoomModal = true" 
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Créer une salle
      </button>
    </div>
    
    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
    
    <!-- Error State -->
    <div v-else-if="error" class="rounded-md bg-red-50 p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Une erreur est survenue</h3>
          <div class="mt-2 text-sm text-red-700">
            <p>{{ error }}</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Empty State -->
    <div v-else-if="rooms.length === 0" class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune salle disponible</h3>
      <p class="mt-1 text-sm text-gray-500">Soyez le premier à créer une salle de discussion !</p>
      <div class="mt-6">
        <button
          type="button"
          @click="showCreateRoomModal = true"
          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Créer une salle
        </button>
      </div>
    </div>
    
    <!-- Rooms Grid -->
    <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <RoomCard
        v-for="room in rooms"
        :key="room.id"
        :room="room"
        class="transform transition-all duration-200 hover:scale-105"
      />
    </div>
    
    <!-- Create Room Modal -->
    <div v-if="showCreateRoomModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showCreateRoomModal = false"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
              <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Créer une nouvelle salle
              </h3>
              <div class="mt-4">
                <form @submit.prevent="createRoom">
                  <div class="space-y-4">
                    <div>
                      <label for="roomName" class="block text-sm font-medium text-gray-700 text-left">Nom de la salle</label>
                      <div class="mt-1">
                        <input
                          id="roomName"
                          v-model="newRoom.name"
                          type="text"
                          required
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="Ma super salle de discussion"
                        />
                      </div>
                    </div>
                    <div>
                      <label for="roomDescription" class="block text-sm font-medium text-gray-700 text-left">Description (optionnelle)</label>
                      <div class="mt-1">
                        <textarea
                          id="roomDescription"
                          v-model="newRoom.description"
                          rows="3"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="Décrivez le sujet de cette salle..."
                        ></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button
                      type="submit"
                      class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
                      :disabled="isCreating"
                    >
                      <span v-if="!isCreating">Créer la salle</span>
                      <svg v-else class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                    </button>
                    <button
                      type="button"
                      @click="showCreateRoomModal = false"
                      class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                    >
                      Annuler
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRoomStore } from '@/store/room';
import RoomCard from './RoomCard.vue';

interface NewRoom {
  name: string;
  description: string;
}

const roomStore = useRoomStore();
// Remove unused imports and variables
const showCreateRoomModal = ref(false);
const newRoom = ref<NewRoom>({
  name: '',
  description: ''
});

const rooms = computed(() => roomStore.rooms);
const isLoading = computed(() => roomStore.isLoading);
const error = computed(() => roomStore.error);

const fetchRooms = async () => {
  await roomStore.fetchRooms();
};

const createRoom = async () => {
  try {
    if (!newRoom.value.name.trim()) return;
    
    await roomStore.createRoom({
      name: newRoom.value.name.trim(),
      description: newRoom.value.description.trim()
    });
    
    // Reset form and close modal
    newRoom.value = { name: '', description: '' };
    showCreateRoomModal.value = false;
  } catch (err) {
    console.error('Failed to create room:', err);
  }
};

onMounted(() => {
  fetchRooms();
});
</script>

<style scoped>
.room-list {
  padding: 1rem;
}

.room-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.create-room-btn {
  padding: 0.5rem 1rem;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.create-room-btn:hover {
  background-color: #45a049;
}

.rooms-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
}

.loading,
.no-rooms,
.error {
  text-align: center;
  padding: 2rem;
  color: #666;
}

.error {
  color: #d32f2f;
}

/* Modal Styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-content h3 {
  margin-top: 0;
  margin-bottom: 1.5rem;
  color: #333;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #444;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group textarea {
  resize: vertical;
  min-height: 80px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
}

.form-actions button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.form-actions button[type="button"] {
  background-color: #f0f0f0;
  color: #333;
}

.form-actions button[type="submit"] {
  background-color: #4CAF50;
  color: white;
}

.form-actions button[type="submit"]:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
