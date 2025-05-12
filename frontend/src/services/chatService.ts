import axios from 'axios';
import type { Message } from '@/types/Message';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:3000/api';

const getAuthHeader = (token: string) => ({
  headers: { Authorization: `Bearer ${token}` }
});

export const chatService = {
  async getMessages(roomId: string, token: string): Promise<Message[]> {
    const response = await axios.get(
      `${API_URL}/rooms/${roomId}/messages`,
      getAuthHeader(token)
    );
    return response.data;
  },

  async sendMessage(
    roomId: string,
    content: string,
    token: string
  ): Promise<Message> {
    const response = await axios.post(
      `${API_URL}/rooms/${roomId}/messages`,
      { content },
      getAuthHeader(token)
    );
    return response.data;
  },

  async deleteMessage(messageId: string, token: string): Promise<void> {
    await axios.delete(
      `${API_URL}/messages/${messageId}`,
      getAuthHeader(token)
    );
  }
};

export default chatService;
