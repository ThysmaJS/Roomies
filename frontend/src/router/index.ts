import { createRouter, createWebHistory } from 'vue-router'
import Register from '../views/Register.vue'
import Login from '@/views/Login.vue'
import Home from '@/views/Home.vue'
import Rooms from '@/views/Rooms.vue'
import RoomPage from '@/views/RoomPage.vue'
import MyRoomsPage from '@/views/MyRoomsPage.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/register',
      name: 'Register',
      component: Register,
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/',
      name: 'Home',
      component: Home,
    },
    {
      path: '/rooms',
      name: 'Rooms',
      component: Rooms,
      meta: { requiresAuth: true },
    },
    {
      path: '/room/:id',
      name: 'RoomPage',
      component: RoomPage,
      meta: { requiresAuth: true }
    },
    {
      path: '/my-rooms',
      name: 'MyRooms',
      component: MyRoomsPage,
      meta: { requiresAuth: true }
    }
    
  ],
})

// 🔐 Global guard pour les routes protégées
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('jwt_token')
  if (to.meta.requiresAuth && !token) {
    next({ path: '/login' })
  } else {
    next()
  }
})

export default router
