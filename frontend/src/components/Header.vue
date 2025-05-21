<template>
    <header class="bg-gradient-to-b from-cyan-300 to-white text-blue-900 font-sans text-sm">
      <div class="flex justify-end gap-4 p-2 text-xs pr-4">
        <a href="/register" class="hover:underline text-blue-700">S'inscrire</a>
        <a href="/login" class="hover:underline text-blue-700">Se connecter</a>
      </div>
  
      <div class="text-center py-2">
        <h1 class="text-xl font-bold text-blue-800">Roomies<span class="text-sm text-blue-500">.com</span></h1>
      </div>
  
      <nav class="bg-cyan-200 border-t border-b border-cyan-300 shadow-inner">
        <ul class="flex justify-center gap-4 py-2 text-blue-800 font-semibold">
          <li><a href="#" class="hover:underline">🏀 Sport</a></li>
          <li><a href="#" class="hover:underline">🔥 Action</a></li>
          <li><a href="#" class="hover:underline">🧠 Réflexion</a></li>
          <li><a href="#" class="hover:underline">🚗 Voiture</a></li>
          <li><a href="#" class="hover:underline">👶 Enfants</a></li>
          <li><a href="#" class="hover:underline">😄 Rigolos</a></li>
        </ul>
      </nav>
    </header>
  </template>
  
  <script setup>
  // aucun script nécessaire pour l’instant
  </script>
  
  <style scoped>
  /* Ajoute une touche "années 2000" */
  header {
    font-family: Arial, sans-serif;
  }
  </style>
  