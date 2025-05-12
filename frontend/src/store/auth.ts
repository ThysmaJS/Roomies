import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { User } from '@/types/User';
import { authService } from '@/services/authService';

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'));
  const user = ref<User | null>(null);

  const isAuthenticated = computed(() => !!token.value);

  function setToken(tokenValue: string) {
    token.value = tokenValue;
    localStorage.setItem('token', tokenValue);
  }

  function setUser(userData: User | null) {
    user.value = userData;
  }

  async function getCurrentUser(): Promise<User> {
    if (!token.value) {
      throw new Error('No authentication token found');
    }
    
    try {
      const userData = await authService.getCurrentUser(token.value);
      user.value = userData;
      return userData;
    } catch (error) {
      console.error('Failed to fetch current user:', error);
      throw error;
    }
  }

  function logout() {
    token.value = null;
    user.value = null;
    localStorage.removeItem('token');
  }

  async function updateProfile(updateData: { username: string; email: string; avatar?: string }) {
    if (!token.value) {
      throw new Error('No authentication token found');
    }
    
    try {
      const updatedUser = await authService.updateProfile(token.value, updateData);
      user.value = { ...user.value, ...updatedUser };
      return updatedUser;
    } catch (error) {
      console.error('Failed to update profile:', error);
      throw error;
    }
  }

  return {
    token,
    user,
    isAuthenticated,
    setToken,
    setUser,
    getCurrentUser,
    logout,
    updateProfile
  };
});
