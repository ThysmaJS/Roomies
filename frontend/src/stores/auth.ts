import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { login as loginService, logout as logoutService, register as registerService } from '@/services/authService';
import type { User } from '@/types/User';

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter();
  const user = ref<User | null>(null);
  const isAuthenticated = ref(false);
  const loading = ref(false);
  const error = ref<string | null>(null);

  // Initialize store from localStorage if available
  const init = () => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      user.value = JSON.parse(storedUser);
      isAuthenticated.value = true;
    }
  };

  // Login user
  const login = async (email: string, password: string) => {
    loading.value = true;
    error.value = null;
    
    try {
      const userData = await loginService(email, password);
      user.value = userData;
      isAuthenticated.value = true;
      localStorage.setItem('user', JSON.stringify(userData));
      router.push('/');
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue lors de la connexion';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  // Register new user
  const register = async (userData: { username: string; email: string; password: string }) => {
    loading.value = true;
    error.value = null;
    
    try {
      const newUser = await registerService(userData);
      user.value = newUser;
      isAuthenticated.value = true;
      localStorage.setItem('user', JSON.stringify(newUser));
      router.push('/');
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue lors de l\'inscription';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  // Logout user
  const logout = async () => {
    try {
      await logoutService();
    } finally {
      user.value = null;
      isAuthenticated.value = false;
      localStorage.removeItem('user');
      router.push('/login');
    }
  };

  // Check if user is authenticated
  const checkAuth = (): boolean => {
    return isAuthenticated.value && user.value !== null;
  };

  return {
    user,
    isAuthenticated,
    loading,
    error,
    init,
    login,
    register,
    logout,
    checkAuth,
  };
});

// Initialize the store when the app starts
export const initAuth = () => {
  const store = useAuthStore();
  store.init();
  return store;
};
