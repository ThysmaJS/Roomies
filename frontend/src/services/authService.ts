import axios from 'axios';
import type { User } from '@/types/User';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:3000/api';

// Types
export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData {
  username: string;
  email: string;
  password: string;
}

// Fonctions exportées individuellement pour le store
export const login = async (email: string, password: string): Promise<User> => {
  const response = await axios.post(`${API_URL}/auth/login`, { email, password });
  // Stocker le token dans le localStorage
  if (response.data.token) {
    localStorage.setItem('token', response.data.token);
  }
  return response.data.user;
};

export const register = async (userData: RegisterData): Promise<User> => {
  const response = await axios.post(`${API_URL}/auth/register`, userData);
  // Stocker le token dans le localStorage
  if (response.data.token) {
    localStorage.setItem('token', response.data.token);
  }
  return response.data.user;
};

export const logout = async (): Promise<void> => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      await axios.post(
        `${API_URL}/auth/logout`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` }
        }
      );
    } catch (error) {
      console.error('Error during logout:', error);
    } finally {
      // Supprimer le token dans tous les cas
      localStorage.removeItem('token');
    }
  }
};

export const getCurrentUser = async (): Promise<User | null> => {
  const token = localStorage.getItem('token');
  if (!token) return null;

  try {
    const response = await axios.get(`${API_URL}/auth/me`, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data.user || null;
  } catch (error) {
    console.error('Error fetching current user:', error);
    // En cas d'erreur (comme un token invalide), on nettoie le token
    if (axios.isAxiosError(error) && error.response?.status === 401) {
      localStorage.removeItem('token');
    }
    return null;
  }
};

export const updateProfile = async (updateData: {
  username: string;
  email: string;
  avatar?: string;
}): Promise<User> => {
  const token = localStorage.getItem('token');
  if (!token) throw new Error('Not authenticated');

  const response = await axios.put(
    `${API_URL}/auth/profile`,
    updateData,
    {
      headers: { Authorization: `Bearer ${token}` }
    }
  );
  return response.data;
};

// Export par défaut pour la rétrocompatibilité
const authService = {
  login,
  register,
  logout,
  getCurrentUser,
  updateProfile,
};

export default authService;
