<template>
  <div 
    @click="selectRoom"
    class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 border border-gray-100 hover:border-indigo-100 cursor-pointer h-full flex flex-col"
  >
    <div class="px-4 py-5 sm:p-6 flex-1 flex flex-col">
      <div class="flex justify-between items-start">
        <h3 class="text-lg leading-6 font-medium text-gray-900 truncate">
          {{ room.name }}
        </h3>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
          {{ room.members.length }} {{ room.members.length > 1 ? 'membres' : 'membre' }}
        </span>
      </div>
      
      <div class="mt-2 flex-1">
        <p v-if="room.description" class="text-sm text-gray-500 line-clamp-3">
          {{ room.description }}
        </p>
        <p v-else class="text-sm text-gray-400 italic">
          Aucune description
        </p>
      </div>
      
      <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center">
          <span class="flex-shrink-0">
            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
              <span class="text-sm font-medium leading-none text-indigo-600">
                {{ room.owner.username.charAt(0).toUpperCase() }}
              </span>
            </span>
          </span>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
              {{ room.owner.username }}
            </p>
            <div class="flex space-x-1 text-sm text-gray-500">
              <time :datetime="room.createdAt">
                {{ formatDate(room.createdAt) }}
              </time>
            </div>
          </div>
        </div>
        <div class="text-indigo-600">
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import type { Room } from '@/types/Room';

const props = defineProps<{
  room: Room;
}>();

const router = useRouter();

const selectRoom = () => {
  router.push({ name: 'room', params: { id: props.room.id } });
};

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric'
  };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};
</script>
