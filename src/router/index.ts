import { getToken, getUser } from '@/helper/authHelper';
import MainLayout from '@/layouts/MainLayout.vue';
import DashboardPage from '@/views/DashboardPage.vue';
import LoginPage from '@/views/LoginPage.vue';
import ProductionOrderPage from '@/views/ProductionOrderPage.vue';
import CreateProductionPlanPage from '@/views/production-plans/CreateProductionPlanPage.vue';
import EditProductionPlanPage from '@/views/production-plans/EditProductionPlanPage.vue';
import ProductionPlanManagerPage from '@/views/production-plans/ProductionPlanManagerPage.vue';
import ProductionPlanPage from '@/views/production-plans/ProductionPlanPage.vue';
import ShowProductionPlanPage from '@/views/production-plans/ShowProductionPlanPage.vue';
import { createRouter, createWebHistory } from 'vue-router';

function checkUserAccess(user: { department: string; role: string } | null, meta: any) {
  if (!user) return false;
  const requiredAccessList = meta.roles as Array<{ department: string; role?: string }> | undefined;

  if (!requiredAccessList || requiredAccessList.length === 0) {
    return true;
  }

  return requiredAccessList.some((access) => {
    const departmentMatch = user.department === access.department;
    const roleMatch = !access.role || user.role === access.role;

    return departmentMatch && roleMatch;
  });
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
          meta: { roles: [{ department: 'PPIC' }] },
        },
        {
          path: 'production-plans/create',
          name: 'production-plans-ppic-create',
          component: CreateProductionPlanPage,
          meta: { roles: [{ department: 'PPIC' }] },
        },
        {
          path: 'production-plans/:id',
          name: 'production-plans-ppic-show',
          component: ShowProductionPlanPage,
          meta: {
            roles: [{ department: 'PPIC' }, { department: 'PRODUCTION', role: 'MANAGER' }],
          },
        },
        {
          path: 'production-plans/:id/edit',
          name: 'production-plans-ppic-edit',
          component: EditProductionPlanPage,
          meta: { roles: [{ department: 'PPIC' }] },
        },
        {
          path: '/approve/production-plans',
          name: 'production-plans-manager',
          component: ProductionPlanManagerPage,
          meta: { roles: [{ department: 'PRODUCTION', role: 'MANAGER' }] },
        },
        {
          path: 'production-orders',
          name: 'production-orders',
          component: ProductionOrderPage,
          meta: { roles: [{ department: 'PRODUCTION' }] },
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
