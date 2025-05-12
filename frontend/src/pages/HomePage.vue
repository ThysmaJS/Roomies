<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <header class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
          <span class="block">Bienvenue sur</span>
          <span class="block text-indigo-600">Roomies</span>
        </h1>
        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
          Trouvez ou créez des salles de discussion pour discuter avec vos amis et collègues.
        </p>
      </header>
      
      <main>
        <RoomList />
      </main>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import RoomList from '@/components/Room/RoomList.vue';

export default defineComponent({
  name: 'HomePage',
  components: {
    RoomList
  }
});
</script>
