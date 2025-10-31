<template>
  <Pagination
    :total="meta.total"
    :siblings="1"
    :show-edges="false"
    :items-per-page="meta.per_page"
    :page="meta.current_page"
    class="w-50"
  >
    <PaginationContent>
      <PaginationPrevious
        @click="meta.current_page > 1 ? emit('update:page', meta.current_page - 1) : null"
        :disabled="meta.current_page === 1"
      />

      <template v-for="link in meta.links" :key="link.label">
        <PaginationItem
          v-if="isPageOrEllipsis(link.label) && link.label !== ''"
          :value="parseInt(link.label)"
          :is-active="link.active"
          @click="handleClick(link)"
        >
          {{ link.label }}
        </PaginationItem>

        <PaginationEllipsis v-else-if="link.label === ''" />
      </template>

      <PaginationNext
        @click="
          meta.current_page < meta.last_page ? emit('update:page', meta.current_page + 1) : null
        "
        :disabled="meta.current_page === meta.last_page"
      />
    </PaginationContent>
  </Pagination>
</template>
<script setup lang="ts">
import type { PaginationLink, PaginationMeta } from '@/interfaces/getAll.interface';
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from './ui/pagination';

defineProps<{
  meta: PaginationMeta;
}>();

const emit = defineEmits<{
  (e: 'update:page', page: number): void;
}>();

const isPageOrEllipsis = (label: string) => {
  return !isNaN(parseInt(label)) || label === '';
};

const handleClick = (link: PaginationLink) => {
  if (link.page) {
    emit('update:page', link.page);
  }
};
</script>
