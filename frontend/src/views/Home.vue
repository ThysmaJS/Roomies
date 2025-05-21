<template>
<div class="flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl mx-auto border-2 border-blue-300 rounded-xl shadow-md p-8 bg-white">
        <h1 class="text-4xl text-blue-700 font-extrabold uppercase tracking-widest mb-6 text-center">
          Bienvenue sur Roomies
        </h1>
  
        <p class="text-lg text-gray-800 mb-4">
          🎮 <strong>Roomies</strong>, c’est bien plus qu’un simple site de jeux. C’est une expérience communautaire
          où tu peux jouer en ligne avec d'autres joueurs **en temps réel**, tout en discutant via un **chat intégré** 🔥.
        </p>
  
        <ul class="list-disc list-inside space-y-3 text-gray-700 text-base">
          <li><span class="font-semibold text-blue-800">Crée ou rejoins des rooms</span> en un clic</li>
          <li><span class="font-semibold text-blue-800">Chat en direct</span> avec tous les joueurs présents dans ta room</li>
          <li><span class="font-semibold text-blue-800">Joue à des mini-jeux</span> (cartes, roulette, paris, etc.) entre amis ou inconnus</li>
          <li><span class="font-semibold text-blue-800">Gagne des coins</span> pour améliorer ton profil</li>
          <li><span class="font-semibold text-blue-800">Classement et avatars personnalisés</span> pour une ambiance unique</li>
        </ul>
  
        <div class="mt-8 flex flex-col sm:flex-row sm:justify-center gap-4">
          <RouterLink
            to="/register"
            class="inline-block px-6 py-3 bg-yellow-400 text-blue-900 font-bold text-center border border-yellow-500 rounded hover:bg-yellow-300 transition"
          >
            ✨ Créer un compte
          </RouterLink>
          <RouterLink
            to="/login"
            class="inline-block px-6 py-3 bg-blue-600 text-white font-bold text-center border border-blue-700 rounded hover:bg-blue-500 transition"
          >
            🔐 Se connecter
          </RouterLink>
        </div>
      </div>
    </div>
  </template>
  