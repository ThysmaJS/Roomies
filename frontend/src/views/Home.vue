<template>
  <div class="flex flex-col items-center justify-center px-4 py-12 space-y-12 font-sans text-blue-900">
    <!-- Section d’accueil -->
    <section class="w-full max-w-4xl border-2 border-cyan-300 rounded-xl shadow-md p-8 bg-white">
      <h1 class="text-4xl font-extrabold uppercase tracking-widest mb-6 text-center text-blue-800">
        Bienvenue sur Roomies
      </h1>
      <p class="text-lg text-gray-800 mb-4 leading-relaxed">
        🎮 <strong>Roomies</strong>, c'est bien plus qu'un simple site de jeux. C'est une expérience communautaire
        où tu peux jouer en ligne avec d'autres joueurs <strong>en temps réel</strong>, tout en discutant via un
        <strong>chat intégré</strong> 🔥.
      </p>
      <ul class="list-disc list-inside space-y-3 text-gray-700 text-base pl-4">
        <li><span class="font-semibold text-blue-800">Crée ou rejoins des rooms</span> en un clic</li>
        <li><span class="font-semibold text-blue-800">Chat en direct</span> avec tous les joueurs présents dans ta room</li>
        <li><span class="font-semibold text-blue-800">Joue à des mini-jeux</span> entre amis ou inconnus</li>
        <li><span class="font-semibold text-blue-800">Gagne des coins</span> pour améliorer ton profil</li>
        <li><span class="font-semibold text-blue-800">Classement et avatars personnalisés</span> pour une ambiance unique</li>
      </ul>
    </section>

    <!-- Carousel des rooms -->
    <RoomCarousel />
  </div>
</template>

<script setup lang="ts">
import RoomCarousel from '@/components/Room/RoomCarousel.vue'
</script>
