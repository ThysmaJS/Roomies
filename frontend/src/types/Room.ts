import type { User } from './User';

export interface Room {
  id: string;
  name: string;
  description?: string;
  owner: User;
  members: User[];
  createdAt: string;
  updatedAt: string;
}
