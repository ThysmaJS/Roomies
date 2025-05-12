import axios from 'axios';
import type { Room } from '@/types/Room';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:3000/api';

const getAuthHeader = (token: string) => ({
  headers: { Authorization: `Bearer ${token}` }
});

export const roomService = {
  async getRooms(token: string): Promise<Room[]> {
    const response = await axios.get(`${API_URL}/rooms`, getAuthHeader(token));
    return response.data;
  },

  async getRoom(id: string, token: string): Promise<Room> {
    const response = await axios.get(`${API_URL}/rooms/${id}`, getAuthHeader(token));
    return response.data;
  },

  async createRoom(data: { name: string; description?: string }, token: string): Promise<Room> {
    const response = await axios.post(`${API_URL}/rooms`, data, getAuthHeader(token));
    return response.data;
  },

  async joinRoom(roomId: string, token: string): Promise<Room> {
    const response = await axios.post(
      `${API_URL}/rooms/${roomId}/join`,
      {},
      getAuthHeader(token)
    );
    return response.data;
  },

  async leaveRoom(roomId: string, token: string): Promise<void> {
    await axios.post(`${API_URL}/rooms/${roomId}/leave`, {}, getAuthHeader(token));
  }
};

export default roomService;
