<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-lg p-8 bg-white rounded-2xl shadow-lg">
      <h1 class="text-3xl font-bold mb-8 text-center text-indigo-700">
        Créer un compte
      </h1>

      <form @submit.prevent="handleRegister" class="space-y-6">
        <!-- Nom d'utilisateur -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
            Nom d'utilisateur
          </label>
          <input
            id="username"
            v-model="form.username"
            required
            placeholder="Ex: thysma"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Adresse e-mail
          </label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            required
            placeholder="Ex: email@example.com"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <!-- Mot de passe -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Mot de passe
          </label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            required
            minlength="6"
            placeholder="Minimum 6 caractères"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <!-- Bouton -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full py-3 text-white font-semibold bg-indigo-600 hover:bg-indigo-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ loading ? "Création en cours..." : "S'inscrire" }}
        </button>

        <!-- Messages -->
        <p v-if="error" class="text-center text-red-600 text-sm mt-2">{{ error }}</p>
        <p v-if="success" class="text-center text-green-600 text-sm mt-2">
          ✅ Inscription réussie !
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
  username: '',
  email: '',
  password: '',
})

const loading = ref(false)
const error = ref('')
const success = ref(false)

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  success.value = false

  try {
    await axios.post('/api/register', form.value, {
      headers: {
        'Content-Type': 'application/json',
      },
    })

    success.value = true
    form.value = { username: '', email: '', password: '' }
  } catch (err) {
    if (err.response?.data?.detail) {
      error.value = err.response.data.detail
    } else {
      error.value = "Erreur lors de l’inscription."
    }
  } finally {
    loading.value = false
  }
}
</script>
