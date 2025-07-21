import { defineStore } from 'pinia'

interface AuthState {
  token: string | null
  userId: number | null
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    token: localStorage.getItem('jwt_token') || null,
    userId: localStorage.getItem('user_id') ? parseInt(localStorage.getItem('user_id')!) : null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    login(token: string, userId: number) {
      this.token = token
      this.userId = userId
      localStorage.setItem('jwt_token', token)
      localStorage.setItem('user_id', userId.toString())
    },
    logout() {
      this.token = null
      this.userId = null
      localStorage.removeItem('jwt_token')
      localStorage.removeItem('user_id')
    },
    restoreFromStorage() {
      const storedId = localStorage.getItem('user_id')
      if (storedId) this.userId = parseInt(storedId)
    },
  },
})
