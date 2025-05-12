<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Créez votre compte
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Ou
        {{ ' ' }}
        <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
          connectez-vous à votre compte existant
        </router-link>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <div>
              <label for="first-name" class="block text-sm font-medium text-gray-700">
                Prénom
              </label>
              <div class="mt-1">
                <input
                  id="first-name"
                  v-model="form.firstName"
                  type="text"
                  autocomplete="given-name"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
              </div>
            </div>

            <div>
              <label for="last-name" class="block text-sm font-medium text-gray-700">
                Nom
              </label>
              <div class="mt-1">
                <input
                  id="last-name"
                  v-model="form.lastName"
                  type="text"
                  autocomplete="family-name"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
              </div>
            </div>
          </div>


          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Adresse email
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="form.email"
                name="email"
                type="email"
                autocomplete="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="votre@email.com"
              >
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Mot de passe
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.password }"
              >
            </div>
            <p v-if="errors.password" class="mt-2 text-sm text-red-600">
              {{ errors.password }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
              Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.
            </p>
          </div>

          <div>
            <label for="confirm-password" class="block text-sm font-medium text-gray-700">
              Confirmez le mot de passe
            </label>
            <div class="mt-1">
              <input
                id="confirm-password"
                v-model="form.confirmPassword"
                name="confirm-password"
                type="password"
                autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.confirmPassword }"
              >
            </div>
            <p v-if="errors.confirmPassword" class="mt-2 text-sm text-red-600">
              {{ errors.confirmPassword }}
            </p>
          </div>

          <div class="flex items-center">
            <input
              id="terms"
              v-model="form.terms"
              name="terms"
              type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              required
            >
            <label for="terms" class="ml-2 block text-sm text-gray-700">
              J'accepte les <a href="#" class="text-indigo-600 hover:text-indigo-500">conditions d'utilisation</a> et la <a href="#" class="text-indigo-600 hover:text-indigo-500">politique de confidentialité</a>.
            </label>
          </div>

          <div>
            <button
              type="submit"
              :disabled="isLoading"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="!isLoading">Créer mon compte</span>
              <svg v-else class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  terms: false
});

const errors = reactive({
  password: '',
  confirmPassword: ''
});

const isLoading = ref(false);
const authStore = useAuthStore();
const router = useRouter();

const validatePassword = (password: string) => {
  // Au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return passwordRegex.test(password);
};

const validateForm = () => {
  let isValid = true;
  
  // Réinitialiser les erreurs
  errors.password = '';
  errors.confirmPassword = '';
  
  // Valider le mot de passe
  if (form.password && !validatePassword(form.password)) {
    errors.password = 'Le mot de passe ne respecte pas les exigences de sécurité.';
    isValid = false;
  }
  
  // Valider la confirmation du mot de passe
  if (form.password && form.confirmPassword && form.password !== form.confirmPassword) {
    errors.confirmPassword = 'Les mots de passe ne correspondent pas.';
    isValid = false;
  }
  
  return isValid;
};

const handleRegister = async () => {
  if (!validateForm()) {
    return;
  }
  
  try {
    isLoading.value = true;
    
    await authStore.register({
      firstName: form.firstName,
      lastName: form.lastName,
      email: form.email,
      password: form.password
    });
    
    // Rediriger vers la page d'accueil après inscription réussie
    router.push('/');
  } catch (err) {
    console.error('Registration error:', err);
    // Gérer les erreurs d'inscription ici
  } finally {
    isLoading.value = false;
  }
};
</script>
