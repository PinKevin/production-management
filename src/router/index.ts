import { getToken, getUser } from '@/helper/authHelper';
import MainLayout from '@/layouts/MainLayout.vue';
import DashboardPage from '@/views/DashboardPage.vue';
import LoginPage from '@/views/LoginPage.vue';
import ProductionOrderPage from '@/views/ProductionOrderPage.vue';
import ProductionPlanManagerPage from '@/views/production-plans/ProductionPlanManagerPage.vue';
import ProductionPlanPage from '@/views/production-plans/ProductionPlanPage.vue';
import { createRouter, createWebHistory } from 'vue-router';

function checkUserAccess(user: { department: string; role: string } | null, meta: any) {
  if (!user) return false;
  const { department, role } = meta;

  if (!department) return true;

  if (user.department !== department) {
    return false;
  }

  if (role && user.role !== role) {
    return false;
  }

  return true;
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: MainLayout,
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'dashboard',
          component: DashboardPage,
        },
        {
          path: 'production-plans',
          name: 'production-plans-ppic',
          component: ProductionPlanPage,
          meta: { department: 'PPIC' },
        },
        {
          path: '/approve/production-plans',
          name: 'production-plans-manager',
          component: ProductionPlanManagerPage,
          meta: { department: 'PRODUCTION', role: 'MANAGER' },
        },
        {
          path: 'production-orders',
          name: 'production-orders',
          component: ProductionOrderPage,
          meta: { department: 'PRODUCTION' },
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
      meta: { requiresAuth: false },
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      redirect: '/',
    },
  ],
});

router.beforeEach((to, _from, next) => {
  const token = getToken();
  const user = getUser();

  if (to.meta.requiresAuth && !token) {
    return next({ name: 'login' });
  }

  if (to.name === 'login' && token) {
    return next({ name: 'dashboard' });
  }

  if (to.meta.requiresAuth && user) {
    const hasAccess = checkUserAccess(user, to.meta);
    if (hasAccess) {
      return next();
    } else {
      return next({ name: 'dashboard' });
    }
  }

  return next();
});

export default router;
