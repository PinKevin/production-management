<template>
  <FullSidebar>
    <RouterView />
  </FullSidebar>
</template>

<script setup lang="ts">
import FullSidebar from '@/components/main/FullSidebar.vue';
import { RouterView } from 'vue-router';
</script>
