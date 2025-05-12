<script setup lang="ts">
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router';
import { computed, ref, onMounted } from 'vue';
import { useAuthStore } from '@/store/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const isMobileMenuOpen = ref(false);

const showNav = computed(() => {
  return route.name !== 'login' && route.name !== 'register';
});

const logout = async () => {
  try {
    await authStore.logout();
    router.push('/login');
  } catch (error) {
    console.error('Erreur lors de la déconnexion :', error);
  }
};

const handleLogoutMobile = async () => {
  isMobileMenuOpen.value = false;
  await logout();
};

// Fermer le menu mobile lors du changement de route
onMounted(() => {
  router.afterEach(() => {
    isMobileMenuOpen.value = false;
  });
});
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Barre de navigation -->
    <header v-if="showNav" class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Logo et navigation principale -->
          <div class="flex">
            <RouterLink to="/" class="flex-shrink-0 flex items-center text-xl font-bold text-indigo-600">
              Roomies
            </RouterLink>
            <nav class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <RouterLink 
                to="/" 
                class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': route.name !== 'home' }"
              >
                Accueil
              </RouterLink>
              <RouterLink 
                v-if="authStore.isAuthenticated"
                to="/profile" 
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-indigo-500 text-gray-900': route.name === 'profile' }"
              >
                Mon Profil
              </RouterLink>
            </nav>
          </div>
          
          <!-- Actions de l'utilisateur -->
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <button 
              v-if="authStore.isAuthenticated"
              @click="logout" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
              </svg>
              Déconnexion
            </button>
            <div v-else class="flex space-x-3">
              <RouterLink 
                to="/login" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
              >
                Connexion
              </RouterLink>
              <RouterLink 
                to="/register" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
              >
                Inscription
              </RouterLink>
            </div>
          </div>
          
          <!-- Bouton du menu mobile -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button 
              type="button" 
              @click="isMobileMenuOpen = !isMobileMenuOpen"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors duration-200"
              aria-controls="mobile-menu" 
              :aria-expanded="isMobileMenuOpen ? 'true' : 'false'"
            >
              <span class="sr-only">Ouvrir le menu principal</span>
              <svg 
                :class="{'hidden': isMobileMenuOpen, 'block': !isMobileMenuOpen}" 
                class="h-6 w-6" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg 
                :class="{'hidden': !isMobileMenuOpen, 'block': isMobileMenuOpen}" 
                class="h-6 w-6" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Menu mobile -->
      <div v-show="isMobileMenuOpen" class="sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
          <RouterLink 
            to="/" 
            @click="isMobileMenuOpen = false"
            class="border-indigo-500 text-indigo-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            :class="{ 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': route.name !== 'home' }"
          >
            Accueil
          </RouterLink>
          <RouterLink 
            v-if="authStore.isAuthenticated"
            to="/profile" 
            @click="isMobileMenuOpen = false"
            class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            :class="{ 'border-indigo-500 text-indigo-700': route.name === 'profile' }"
          >
            Mon Profil
          </RouterLink>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
          <div class="mt-3 space-y-1">
            <button 
              v-if="authStore.isAuthenticated"
              @click="handleLogoutMobile" 
              class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
            >
              Déconnexion
            </button>
            <template v-else>
              <RouterLink 
                to="/login" 
                @click="isMobileMenuOpen = false"
                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
              >
                Connexion
              </RouterLink>
              <RouterLink 
                to="/register" 
                @click="isMobileMenuOpen = false"
                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
              >
                Inscription
              </RouterLink>
            </template>
          </div>
        </div>
      </div>
    </header>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <RouterView />
    </main>
    
    <!-- Pied de page -->
    <footer class="bg-white border-t border-gray-200 mt-12">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-gray-500">
          &copy; {{ new Date().getFullYear() }} Roomies. Tous droits réservés.
        </p>
      </div>
    </footer>
  </div>
</template>

<style>
/* Animation pour les transitions */
.transition-colors {
  transition-property: background-color, border-color, color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Styles pour les transitions de page */
.page-enter-active,
.page-leave-active {
  transition: opacity 0.2s ease;
}

.page-enter-from,
.page-leave-to {
  opacity: 0;
}

/* Styles pour les transitions de liste */
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Styles pour les notifications */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from,
.notification-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
