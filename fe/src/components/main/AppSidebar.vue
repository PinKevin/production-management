<template>
  <Sidebar>
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <RouterLink to="/">
              <div
                class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg"
              >
                <GalleryVerticalEnd class="size-4" />
              </div>
              <div class="flex flex-col gap-0.5 leading-none">
                <span class="font-medium">Production Management</span>
              </div>
            </RouterLink>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>
    <SidebarContent>
      <SidebarMenu>
        <SidebarMenuItem v-for="link in nav" :key="link.title">
          <SidebarMenuButton as-child>
            <RouterLink :to="link.url">
              {{ link.title }}
            </RouterLink>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarContent>
    <SidebarFooter>
      <NavUser />
    </SidebarFooter>
  </Sidebar>
</template>

<script setup lang="ts">
import { GalleryVerticalEnd } from 'lucide-vue-next';
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '../ui/sidebar';
import NavUser from './NavUser.vue';
import { computed, onMounted, ref } from 'vue';
import type { User } from '@/interfaces/user.interface';
import { getUser } from '@/helper/authHelper';
import { RouterLink } from 'vue-router';

const user = ref<User | null>(null);

onMounted(() => {
  user.value = getUser();
});

const isPpic = computed(() => user.value?.department === 'PPIC');
const isProdManager = computed(
  () => user.value?.department === 'PRODUCTION' && user.value?.role === 'MANAGER',
);
const isProdStaff = computed(
  () => user.value?.department === 'PRODUCTION' && user.value?.role === 'STAFF',
);

const nav = computed(() => {
  const ppic = isPpic.value;
  const prodManager = isProdManager.value;
  const prodStaff = isProdStaff.value;

  const allLinks = [
    {
      title: 'Dashboard',
      url: '/',
      canView: true,
    },
    {
      title: 'Production Plan',
      url: '/production-plans',
      canView: ppic,
    },
    {
      title: 'Production Plan',
      url: '/approve/production-plans',
      canView: prodManager,
    },
    {
      title: 'Production Order',
      url: '/production-orders',
      canView: prodStaff || prodManager,
    },
  ];

  return allLinks.filter((link) => link.canView);
});
</script>
