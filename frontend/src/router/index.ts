import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/store/auth';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/pages/HomePage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/pages/LoginPage.vue'),
    meta: { guest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/pages/RegisterPage.vue'),
    meta: { guest: true }
  },
  {
    path: '/room/:id',
    name: 'room',
    component: () => import('@/pages/RoomPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/pages/ProfilePage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

// Navigation guard
router.beforeEach((to, _from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;

  // Si l'utilisateur essaie d'accéder à une page protégée et n'est pas connecté
  if (to.matched.some(record => record.meta.requiresAuth && record.path !== '/')) {
    if (!isAuthenticated) {
      next({ name: 'login', query: { redirect: to.fullPath } });
      return;
    }
  }
  
  // Si l'utilisateur est connecté et essaie d'accéder à la page de connexion ou d'inscription
  if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
    next({ name: 'home' });
    return;
  }

  // Pour toutes les autres routes, y compris la page d'accueil
  next();
});

export default router;
