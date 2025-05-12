<template>
  <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Mon Profil
          </h1>
          <p class="mt-1 text-sm text-gray-500">
            Gérez vos informations personnelles et vos préférences de compte
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <router-link 
            to="/"
            class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à l'accueil
          </router-link>
        </div>
      </div>
      
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <UserProfile />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useRouter } from 'vue-router';
import UserProfile from '@/components/User/UserProfile.vue';

export default defineComponent({
  name: 'ProfilePage',
  components: {
    UserProfile
  },
  setup() {
    const router = useRouter();
    
    return {
      router
    };
  }
});
</script>
