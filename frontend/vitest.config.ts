import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  test: {
    environment: 'jsdom', // 👈 Obligatoire pour le DOM dans les tests
    globals: true,         // (optionnel, utile si tu veux describe/it/globals sans import)
    include: ['src/**/*.spec.{js,ts}'], // tes fichiers de test
  },
})
