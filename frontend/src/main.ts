import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createI18n } from 'vue-i18n';
import App from './App.vue';
import router from './router';
import { useAuthStore } from './store/auth';
import { socketService } from './sockets/socket';
import './assets/styles/tailwind.css';

// Create app
const app = createApp(App);

// Create Pinia store
const pinia = createPinia();
app.use(pinia);

// Initialize auth store and check authentication
const authStore = useAuthStore();

// Initialize i18n
const i18n = createI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en: {
      // Add your translations here
    }
  }
});


// Use plugins
app.use(router);
app.use(i18n);


// Initialize socket connection if user is authenticated
if (authStore.token) {
  socketService.initialize(authStore.token);
  
  // Fetch current user data
  authStore.getCurrentUser().catch((error) => {
    console.error('Failed to fetch user data:', error);
    authStore.logout();
  });
}

// Mount the app
app.mount('#app');

// Global error handler
app.config.errorHandler = (err) => {
  console.error('Unhandled error:', err);
};

// Global properties
app.config.globalProperties.$filters = {
  formatDate(value: string) {
    if (!value) return '';
    return new Date(value).toLocaleDateString();
  },
  formatDateTime(value: string) {
    if (!value) return '';
    return new Date(value).toLocaleString();
  }
};

// Global directives
app.directive('focus', {
  mounted(el) {
    el.focus();
  }
});
