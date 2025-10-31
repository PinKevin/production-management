<template>
  <div v-if="isLoading || !user" class="flex items-center gap-2 p-2">
    <Skeleton class="h-8 w-8 rounded-lg" />
    <div class="flex flex-col gap-1">
      <Skeleton class="h-4 w-20" />
      <Skeleton class="h-3 w-16" />
    </div>
  </div>

  <SidebarMenu v-else-if="user">
    <SidebarMenuItem>
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <SidebarMenuButton
            size="lg"
            class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
          >
            <UserDisplay :user="user" />
            <ChevronsUpDown class="ml-auto size-4" />
          </SidebarMenuButton>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
          :side="isMobile ? 'bottom' : 'right'"
          align="end"
          :sideOffset="4"
        >
          <DropdownMenuLabel>
            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
              <UserDisplay :user="user" />
            </div>
          </DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem @click="handleLogout" class="focus:bg-red-500 focus:text-white">
            <LogOut class="mr-2 h-4 w-4" />
            <span>Log out</span>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </SidebarMenuItem>
  </SidebarMenu>

  <div v-else class="p-2 text-xs text-red-500">Failed to load user data</div>
</template>

<script setup lang="ts">
import type { User } from '@/interfaces/user.interface';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '../ui/sidebar';
import { clearAuthData, getUser, removeToken } from '@/helper/authHelper';
import { Skeleton } from '../ui/skeleton';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '../ui/dropdown-menu';
import { ChevronsUpDown, LogOut } from 'lucide-vue-next';
import UserDisplay from './UserDisplay.vue';

const user = ref<User | null>(null);
const isLoading = ref(true);

const router = useRouter();
const { isMobile } = useSidebar();

onMounted(() => {
  isLoading.value = true;

  try {
    const userData = getUser();

    if (userData && userData.id) {
      user.value = userData;
    } else {
      throw new Error('User not found');
    }
  } catch (error) {
    handleLogout();
  } finally {
    isLoading.value = false;
  }
});

const handleLogout = () => {
  clearAuthData();
  router.push('/login');
};
</script>
