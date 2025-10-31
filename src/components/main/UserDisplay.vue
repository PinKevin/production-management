<template>
  <div v-if="user" class="flex items-center gap-2">
    <Avatar class="h-8 w-8 rounded-lg">
      <AvatarFallback class="rounded-lg"> UN </AvatarFallback>
    </Avatar>
    <div class="grid flex-1 text-left text-sm leading-tight">
      <span class="truncate font-semibold">{{ user.name }}</span>
      <span class="truncate text-xs font-normal">{{ user.role }} - {{ user.department }}</span>
    </div>
  </div>

  <div v-else class="text-xs text-gray-500">User data not available</div>
</template>

<script setup lang="ts">
import type { User } from '@/interfaces/user.interface';
import { Avatar, AvatarFallback } from '../ui/avatar';

defineProps<{
  user: User | null;
}>();
</script>
