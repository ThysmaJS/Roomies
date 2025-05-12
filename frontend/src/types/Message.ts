import type { User } from './User';

export interface Message {
  id: string;
  content: string;
  roomId: string;
  user: User;
  createdAt: string;
  updatedAt: string;
}
