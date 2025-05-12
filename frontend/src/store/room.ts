import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { Room } from '@/types/Room';

interface CreateRoomData {
  name: string;
  description: string;
}

export const useRoomStore = defineStore('room', () => {
  const rooms = ref<Room[]>([]);
  const currentRoom = ref<Room | null>(null);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  async function fetchRooms() {
    try {
      isLoading.value = true;
      error.value = null;
      // TODO: Implement API call to fetch rooms
      // const response = await roomService.getRooms();
      // rooms.value = response.data;
    } catch (err) {
      error.value = 'Failed to fetch rooms';
      console.error('Error fetching rooms:', err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function createRoom(roomData: CreateRoomData) {
    try {
      isLoading.value = true;
      error.value = null;
      // TODO: Implement API call to create room
      // const response = await roomService.createRoom(roomData);
      // const newRoom = response.data;
      // rooms.value.push(newRoom);
      // return newRoom;
      
      // Temporary implementation until API is ready
      const newRoom: Room = {
        id: Date.now().toString(),
        name: roomData.name,
        description: roomData.description,
        owner: {
          id: 'current-user-id',
          username: 'Current User',
          email: 'user@example.com',
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString()
        },
        members: [],
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      };
      
      rooms.value.push(newRoom);
      return newRoom;
    } catch (err) {
      error.value = 'Failed to create room';
      console.error('Error creating room:', err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  function setCurrentRoom(room: Room | null) {
    currentRoom.value = room;
  }

  function addRoom(room: Room) {
    rooms.value.push(room);
  }

  return {
    rooms,
    currentRoom,
    isLoading,
    error,
    fetchRooms,
    createRoom,
    setCurrentRoom,
    addRoom
  };
});
