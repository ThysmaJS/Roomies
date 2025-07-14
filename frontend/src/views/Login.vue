<template>
<div class="flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg p-6 border-2 border-blue-300 rounded-md shadow-lg bg-white">
        <h1 class="text-3xl text-blue-700 font-extrabold mb-6 uppercase tracking-wide">
          Connexion
        </h1>
  
        <form @submit.prevent="handleLogin" class="space-y-5 text-sm text-blue-900 font-medium">
          <div>
            <label for="username" class="block mb-1">Nom d'utilisateur :</label>
            <input
              id="username"
              type="text"
              v-model="form.username"
              required
              class="w-full px-3 py-2 border border-blue-300 rounded bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
          </div>
  
          <div>
            <label for="password" class="block mb-1">Mot de passe :</label>
            <input
              id="password"
              type="password"
              v-model="form.password"
              required
              class="w-full px-3 py-2 border border-blue-300 rounded bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
          </div>
  
          <p v-if="error" class="text-red-600 text-xs">{{ error }}</p>
  
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2 bg-yellow-400 text-blue-900 font-bold rounded border border-yellow-500 hover:bg-yellow-300 disabled:opacity-50 transition"
          >
            {{ loading ? "Connexion..." : "Se connecter" }}
          </button>
  
          <p class="text-sm mt-2">
            Pas encore de compte ?
            <RouterLink to="/register" class="text-blue-700 underline hover:text-blue-500">Inscris-toi</RouterLink>
          </p>
        </form>
      </div>
    </div>
  </template>
  
<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const form = ref({ username: '', password: '' })
const loading = ref(false)
const error = ref('')

const auth = useAuthStore()

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    const { data } = await axios.post('/api/login', form.value)
    auth.login(data.token, data.user.id) // ✅ corriger ici
    router.push('/')
  } catch (err) {
    console.error('Erreur login :', err)
    error.value = 'Email ou mot de passe invalide.'
  } finally {
    loading.value = false
  }
}
</script>

  