import { io, type Socket } from 'socket.io-client';
import type { Message } from '@/types/Message';

export class SocketService {
  private socket: Socket | null = null;

  initialize(token: string): void {
    if (this.socket?.connected) return;
    this.socket = io(import.meta.env.VITE_SOCKET_URL || 'http://localhost:3000', {
      auth: { token },
      transports: ['websocket']
    });
  }

  disconnect(): void {
    if (this.socket) {
      this.socket.disconnect();
      this.socket = null;
    }
  }

  // Room events
  joinRoom(roomId: string): void {
    this.socket?.emit('join_room', { roomId });
  }

  leaveRoom(roomId: string): void {
    this.socket?.emit('leave_room', { roomId });
  }

  // Message events
  sendMessage(roomId: string, content: string): void {
    this.socket?.emit('send_message', { roomId, content });
  }

  // Listeners
  onMessageReceived(callback: (message: Message) => void): void {
    this.socket?.on('message', callback);
  }

  off(event: string, callback: (...args: any[]) => void): void {
    this.socket?.off(event, callback);
  }

  onUserJoined(callback: (user: any) => void): void {
    this.socket?.on('user_joined', callback);
  }

  onUserLeft(callback: (user: any) => void): void {
    this.socket?.on('user_left', callback);
  }

  onError(callback: (error: Error) => void): void {
    this.socket?.on('error', callback);
  }

  // Cleanup
  removeListeners(): void {
    if (this.socket) {
      this.socket.off('new_message');
      this.socket.off('user_joined');
      this.socket.off('user_left');
      this.socket.off('error');
    }
  }
}

export const socketService = new SocketService();
