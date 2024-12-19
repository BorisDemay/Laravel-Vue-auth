import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../components/Login.vue'
import RegisterView from '../components/Register.vue'
import Cart from '../components/Cart.vue'
import DashboardView from '@/views/DashboardView.vue'
import { useUserStore } from '../stores/user'
import Success from '@/components/Success.vue'
import Cancel from '@/components/Cancel.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/',
      name: 'dashboard',
      component: DashboardView,
    },
    {
      path: '/cart',
      name: 'cart',
      component: Cart,
    },
    {
      path: '/success',
      name: 'success',
      component: () => Success,
      beforeEnter: (to, from, next) => {
        if (!to.query.session_id) {
          next('/');
        } else {
          next();
        }
      },
    },
    {
      path: '/cancel',
      name: 'cancel',
      component: () => Cancel,
      beforeEnter: (to, from, next) => {
        if (!to.query.session_id) {
          next('/');
        } else {
          next();
        }
      },
    },
  ],
})

router.beforeEach((to, from, next) => {
  const userStore = useUserStore();
  const isAuthenticated = !!localStorage.getItem('token');

  if ((to.path === '/login' || to.path === '/register') && isAuthenticated) {
    next('/dashboard');
  } else if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});


export default router
