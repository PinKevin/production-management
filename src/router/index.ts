import { getToken } from '@/helper/authHelper';
import MainLayout from '@/layouts/MainLayout.vue';
import LoginPage from '@/views/LoginPage.vue';
import PlanPage from '@/views/PlanPage.vue';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: MainLayout,
      children: [
        {
          path: 'production-plans',
          name: 'production-plans',
          component: PlanPage,
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
    },
  ],
});

router.beforeEach((to, _from, next) => {
  const token = getToken();

  if (to.name !== 'login' && !token) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
