import LoginPage from '@/views/LoginPage.vue';
import PlanPage from '@/views/PlanPage.vue';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login',
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
    },
    {
      path: '/production-plans',
      name: 'production-plans',
      component: PlanPage,
    },
  ],
});

export default router;
