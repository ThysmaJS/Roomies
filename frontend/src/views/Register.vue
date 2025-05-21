<template>
<div class="flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-lg p-6 border-2 border-blue-300 rounded-md shadow-lg bg-white">
      <h1 class="text-3xl text-blue-700 font-extrabold mb-6 uppercase tracking-wide">
        Créer un compte
      </h1>

      <form @submit.prevent="handleRegister" class="space-y-5 text-sm text-blue-900 font-medium">
        <div>
          <label for="username" class="block mb-1">Nom d'utilisateur :</label>
          <input
            id="username"
            v-model="form.username"
            required
            placeholder="ex: thysma"
            class="w-full px-3 py-2 border border-blue-300 rounded bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          <p v-if="error && !form.username" class="text-red-600 text-xs mt-1">{{ error }}</p>
        </div>

        <div>
          <label for="email" class="block mb-1">Adresse e-mail :</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            required
            placeholder="ex: email@example.com"
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
            minlength="6"
            placeholder="Minimum 6 caractères"
            class="w-full px-3 py-2 border border-blue-300 rounded bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
        </div>

        <p v-if="error" class="text-red-600 text-xs">{{ error }}</p>
        <p v-if="success" class="text-green-600 text-xs">✅ Inscription réussie !</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-2 bg-yellow-400 text-blue-900 font-bold rounded border border-yellow-500 hover:bg-yellow-300 disabled:opacity-50 transition"
        >
          {{ loading ? "Création en cours..." : "S'inscrire" }}
        </button>

        <p class="text-sm mt-2">
          Déjà un compte ?
          <RouterLink to="/login" class="text-blue-700 underline hover:text-blue-500">Connecte-toi</RouterLink>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({ username: '', email: '', password: '' })
const loading = ref(false)
const error = ref('')
const success = ref(false)

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  success.value = false

  try {
    await axios.post('/api/register', form.value)
    success.value = true
    form.value = { username: '', email: '', password: '' }
    setTimeout(() => router.push('/login'), 1000)
  } catch (err) {
    error.value = err.response?.data?.detail || "Erreur lors de l’inscription."
  } finally {
    loading.value = false
  }
}
</script>
