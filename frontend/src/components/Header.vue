<template>
  <header class="bg-gradient-to-b from-cyan-300 to-white text-blue-900 font-sans text-sm">
    <!-- Bandeau connexion/déconnexion -->
    <div class="flex justify-end items-center gap-4 p-2 text-xs pr-4">
      <template v-if="isAuthenticated">
        <RouterLink to="/rooms" class="hover:underline text-blue-700 font-semibold">🎮 Rooms</RouterLink>
        <button @click="logout" class="hover:underline text-red-600 font-semibold">🚪 Déconnexion</button>
        <RouterLink to="/my-rooms" class="hover:underline text-blue-700 font-semibold">📁 Mes Rooms</RouterLink>
      </template>
      <template v-else>
        <RouterLink to="/register" class="hover:underline text-blue-700">S'inscrire</RouterLink>
        <RouterLink to="/login" class="hover:underline text-blue-700">Se connecter</RouterLink>
      </template>
    </div>

    <!-- Logo cliquable -->
    <div class="text-center py-2">
      <RouterLink to="/" class="inline-block">
        <h1 class="text-xl font-bold text-blue-800">
          Roomies<span class="text-sm text-blue-500">.com</span>
        </h1>
      </RouterLink>
    </div>

    <!-- Navigation -->
    <nav class="bg-cyan-200 border-t border-b border-cyan-300 shadow-inner">
    </nav>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const { isAuthenticated } = storeToRefs(auth)
const router = useRouter()

function logout() {
  auth.logout()
  router.push('/login')
}
</script>

<style scoped>
header {
  font-family: Arial, sans-serif;
}
</style>
