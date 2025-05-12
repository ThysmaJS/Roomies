import { fileURLToPath, URL } from 'node:url'
import path from 'node:path'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      '~': path.resolve(__dirname, './src')
    },
  },
  // PostCSS est configuré via postcss.config.js
  server: {
    host: '0.0.0.0',  // ➔ c'est ça qui permet d'accéder depuis ton PC
    port: 5173,       // ➔ en cohérence avec ton docker-compose.yml
    strictPort: true, // ➔ optionnel : force l'erreur si 5173 est déjà occupé
  },
})
