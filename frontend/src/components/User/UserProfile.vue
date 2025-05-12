<template>
  <div class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <!-- En-tête du profil -->
      <div v-if="user" class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row items-center">
          <div class="relative">
            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden">
              <img 
                v-if="user.avatar" 
                :src="user.avatar" 
                :alt="user.username"
                class="h-full w-full object-cover"
              >
              <span v-else class="text-3xl font-bold text-indigo-600">
                {{ user.username.charAt(0).toUpperCase() }}
              </span>
            </div>
            <button 
              @click="$refs.avatarInput.click()"
              class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-md border border-gray-200 hover:bg-gray-50"
            >
              <svg class="h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <input 
                ref="avatarInput"
                type="file" 
                class="hidden" 
                accept="image/*"
                @change="handleAvatarUpload"
              >
            </button>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left">
            <h1 class="text-2xl font-bold text-gray-900">{{ user.username }}</h1>
            <p class="text-sm text-gray-500">{{ user.email }}</p>
            <p class="text-xs text-gray-400 mt-1">
              Membre depuis le {{ formatDate(user.createdAt) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Formulaire de mise à jour du profil -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Paramètres du compte
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Gérez vos informations personnelles et vos préférences.
          </p>
        </div>
        
        <form @submit.prevent="updateProfile" class="px-4 py-5 sm:p-6">
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="username" class="block text-sm font-medium text-gray-700">
                Nom d'utilisateur
              </label>
              <div class="mt-1">
                <input
                  id="username"
                  v-model="editForm.username"
                  type="text"
                  required
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                >
              </div>
            </div>

            <div class="sm:col-span-4">
              <label for="email" class="block text-sm font-medium text-gray-700">
                Adresse email
              </label>
              <div class="mt-1">
                <input
                  id="email"
                  v-model="editForm.email"
                  type="email"
                  required
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                >
              </div>
            </div>

            <div class="sm:col-span-6">
              <label for="bio" class="block text-sm font-medium text-gray-700">
                À propos de moi
              </label>
              <div class="mt-1">
                <textarea
                  id="bio"
                  v-model="editForm.bio"
                  rows="3"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  placeholder="Décrivez-vous en quelques mots..."
                ></textarea>
              </div>
            </div>
          </div>

          <div class="mt-8 flex justify-end space-x-3">
            <button 
              type="button" 
              @click="resetForm"
              :disabled="!hasChanges || isUpdating"
              class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Annuler
            </button>
            <button 
              type="submit" 
              :disabled="!hasChanges || isUpdating"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isUpdating" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Enregistrement...
              </span>
              <span v-else>Enregistrer les modifications</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Zone de danger -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-red-200 bg-red-50 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-red-800">
            Zone de danger
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-red-700">
            Ces actions sont irréversibles. Soyez certain de ce que vous faites.
          </p>
        </div>
        
        <div class="px-4 py-5 sm:p-6 space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="text-base font-medium text-gray-900">Déconnexion</h4>
              <p class="text-sm text-gray-500">Déconnectez-vous de votre compte.</p>
            </div>
            <button 
              @click="confirmLogout"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
              Se déconnecter
            </button>
          </div>
          
          <div class="border-t border-gray-200"></div>
          
          <div class="flex items-center justify-between">
            <div>
              <h4 class="text-base font-medium text-red-700">Supprimer le compte</h4>
              <p class="text-sm text-red-500">Cette action est irréversible et supprimera toutes vos données.</p>
            </div>
            <button 
              @click="confirmDeleteAccount"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
              Supprimer mon compte
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Boîte de dialogue de confirmation -->
    <div v-if="showConfirmDialog" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="handleCancel"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                {{ confirmDialog.title }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  {{ confirmDialog.message }}
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button 
              type="button" 
              @click="handleConfirm"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
            >
              {{ confirmDialog.confirmText }}
            </button>
            <button 
              type="button" 
              @click="handleCancel"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
            >
              {{ confirmDialog.cancelText }}
            </button>
          </div>
        </div>
      </div>
    </div>
            {{ confirmDialog.confirmText }}
          </button>
        </div>
      </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useToast } from 'vue-toastification';

const authStore = useAuthStore();
const router = useRouter();
const toast = useToast();
const avatarInput = ref<HTMLInputElement | null>(null);

const user = computed(() => authStore.user);
const isUpdating = ref(false);

interface EditForm {
  username: string;
  email: string;
  bio?: string;
  avatar?: string;
}

const editForm = ref<EditForm>({
  username: user.value?.username || '',
  email: user.value?.email || '',
  bio: user.value?.bio || '',
  avatar: user.value?.avatar || ''
});

const hasChanges = computed(() => {
  if (!user.value) return false;
  return (
    editForm.value.username !== user.value.username ||
    editForm.value.email !== user.value.email ||
    editForm.value.bio !== (user.value.bio || '') ||
    editForm.value.avatar !== (user.value.avatar || '')
  );
});

const updateProfile = async () => {
  if (!user.value || !hasChanges.value) return;
  
  try {
    isUpdating.value = true;
    await authStore.updateProfile(editForm.value);
    toast.success('Profil mis à jour avec succès');
  } catch (error) {
    console.error('Erreur lors de la mise à jour du profil :', error);
    toast.error('Erreur lors de la mise à jour du profil');
  } finally {
    isUpdating.value = false;
  }
};

const handleAvatarUpload = async (event: Event) => {
  const input = event.target as HTMLInputElement;
  if (!input.files || input.files.length === 0) return;

  const file = input.files[0];
  const formData = new FormData();
  formData.append('avatar', file);

  try {
    isUpdating.value = true;
    // Ici, vous devrez implémenter l'upload du fichier vers votre serveur
    // Par exemple :
    // const response = await api.uploadAvatar(formData);
    // editForm.value.avatar = response.data.url;
    // await updateProfile();
    
    // Pour l'instant, on simule un upload réussi
    const reader = new FileReader();
    reader.onload = (e) => {
      if (e.target?.result) {
        editForm.value.avatar = e.target.result as string;
        updateProfile();
      }
    };
    reader.readAsDataURL(file);
    
    toast.success('Photo de profil mise à jour');
  } catch (error) {
    console.error('Erreur lors du téléchargement de l\'avatar :', error);
    toast.error('Erreur lors du téléchargement de la photo de profil');
  } finally {
    isUpdating.value = false;
    // Réinitialiser l'input pour permettre le téléchargement du même fichier
    if (avatarInput.value) {
      avatarInput.value.value = '';
    }
  }
};

const resetForm = () => {
  if (!user.value) return;
  editForm.value = {
    username: user.value.username,
    email: user.value.email,
    bio: user.value.bio || '',
    avatar: user.value.avatar || ''
  };
};

interface ConfirmDialog {
  title: string;
  message: string;
  confirmText: string;
  cancelText: string;
  action: (() => Promise<void>) | (() => void) | null;
}

const confirmDialog = ref<ConfirmDialog>({
  title: '',
  message: '',
  confirmText: 'Confirmer',
  cancelText: 'Annuler',
  action: null
});

const showConfirmDialog = ref(false);

const showConfirm = (title: string, message: string, action: () => void) => {
  confirmDialog.value = {
    title,
    message,
    confirmText: 'Confirmer',
    cancelText: 'Annuler',
    action
  };
  showConfirmDialog.value = true;
};

const handleConfirm = async () => {
  if (confirmDialog.value.action) {
    try {
      await Promise.resolve(confirmDialog.value.action());
    } catch (error) {
      console.error('Erreur lors de l\'exécution de l\'action :', error);
    }
  }
  showConfirmDialog.value = false;
};

const handleCancel = () => {
  showConfirmDialog.value = false;
};

const logout = async () => {
  try {
    await authStore.logout();
    router.push('/login');
    toast.success('Vous avez été déconnecté avec succès');
  } catch (error) {
    console.error('Erreur lors de la déconnexion :', error);
    toast.error('Erreur lors de la déconnexion');
  }
};

const deleteAccount = async () => {
  try {
    await authStore.deleteAccount();
    toast.success('Votre compte a été supprimé avec succès');
    router.push('/');
  } catch (error) {
    console.error('Erreur lors de la suppression du compte :', error);
    toast.error('Erreur lors de la suppression du compte');
  }
};

const confirmLogout = () => {
  showConfirm(
    'Se déconnecter',
    'Êtes-vous sûr de vouloir vous déconnecter ?',
    logout
  );
};

const confirmDeleteAccount = () => {
  showConfirm(
    'Supprimer le compte',
    'Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et toutes vos données seront perdues.',
    deleteAccount
  );
};

const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const options: Intl.DateTimeFormatOptions = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};

onMounted(() => {
  if (user.value) {
    resetForm();
  }
});
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
