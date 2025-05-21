<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
      <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-indigo-700">Connexion</h1>
  
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              id="email"
              type="email"
              v-model="form.email"
              required
              placeholder="ex: alice@example.com"
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
  
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input
              id="password"
              type="password"
              v-model="form.password"
              required
              minlength="6"
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
  
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 disabled:opacity-50"
          >
            {{ loading ? 'Connexion...' : 'Se connecter' }}
          </button>
  
          <p v-if="error" class="text-sm text-red-600 text-center mt-2">{{ error }}</p>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  
  const form = ref({
    email: '',
    password: ''
  })
  
  const loading = ref(false)
  const error = ref('')
  
  const handleLogin = async () => {
    loading.value = true
    error.value = ''
  
    try {
      const response = await axios.post('/api/login', {
        email: form.value.email,
        password: form.value.password
      })
  
      const token = response.data.token
  
      // Stocke le token en localStorage
      localStorage.setItem('jwt_token', token)
  
      // Redirige vers la page d'accueil (ou autre)
      router.push('/')
    } catch (err) {
      error.value = 'Email ou mot de passe invalide.'
    } finally {
      loading.value = false
    }
  }
  </script>
  