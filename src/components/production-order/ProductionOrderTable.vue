<template>
  <div class="flex flex-row justify-between items-center">
    <h1 class="mb-4 text-2xl font-bold">{{ pageTitle }}</h1>

    <div class="flex flex-row gap-2">
      <Select
        :model-value="statusFilter ?? 'ALL'"
        @update:model-value="(v) => onFilterChange(v as OrderStatus | 'ALL')"
      >
        <SelectTrigger>
          <SelectValue placeholder="Filter berdasarkan status"> </SelectValue>
        </SelectTrigger>
        <SelectContent>
          <SelectItem v-for="option in statusOptions" :key="option.value" :value="option.value">
            {{ option.label }}
          </SelectItem>
        </SelectContent>
      </Select>
    </div>
  </div>

  <Table>
    <TableHeader>
      <TableRow>
        <TableHead>No.</TableHead>
        <TableHead>Produk</TableHead>
        <TableHead>Jumlah Rencana</TableHead>
        <TableHead>
          <div class="flex items-center space-x-2">
            <span>Deadline</span>
            <Button variant="ghost" size="sm" @click="handleSort('deadline')" class="p-0 h-auto">
              <ChevronUp
                :class="
                  cn(
                    'w-4 h-4 transition-colors',
                    sortParams.field === 'deadline' && sortParams.order === 'ASC'
                      ? 'text-blue-600'
                      : 'text-gray-400 hover:text-gray-700',
                  )
                "
              />
              <ChevronDown
                :class="
                  cn(
                    'w-4 h-4 transition-colors',
                    sortParams.field === 'deadline' && sortParams.order === 'DESC'
                      ? 'text-blue-600'
                      : 'text-gray-400 hover:text-gray-700',
                  )
                "
              />
            </Button>
          </div>
        </TableHead>
        <TableHead>
          <div class="flex items-center space-x-2">
            <span>Dibuat Pada</span>
            <Button variant="ghost" size="sm" @click="handleSort('created_at')" class="p-0 h-auto">
              <ChevronUp
                :class="
                  cn(
                    'w-4 h-4 transition-colors',
                    sortParams.field === 'created_at' && sortParams.order === 'ASC'
                      ? 'text-blue-600'
                      : 'text-gray-400 hover:text-gray-700',
                  )
                "
              />
              <ChevronDown
                :class="
                  cn(
                    'w-4 h-4 transition-colors',
                    sortParams.field === 'created_at' && sortParams.order === 'DESC'
                      ? 'text-blue-600'
                      : 'text-gray-400 hover:text-gray-700',
                  )
                "
              />
            </Button>
          </div>
        </TableHead>
        <TableHead>Status</TableHead>
        <TableHead>Aksi</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <template v-if="isLoading">
        <TableRow>
          <TableCell :colspan="7" class="text-center py-8 text-gray-500">
            Memuat data...
          </TableCell>
        </TableRow>
      </template>

      <template v-else-if="data.length > 0">
        <TableRow v-for="(datum, index) in data" :key="datum.id">
          <TableCell>{{ meta ? meta.from + index : index + 1 }}.</TableCell>
          <TableCell>{{ datum.product.name }}</TableCell>
          <TableCell>{{ datum.quantityPlanned }}</TableCell>
          <TableCell>{{ formatDate(datum.deadline) }}</TableCell>
          <TableCell>{{ formatDate(datum.createdAt) }}</TableCell>
          <TableCell>
            <span
              :class="
                cn(
                  'font-semibold px-2 py-0.5 rounded-full text-xs',
                  statusDisplayMap[datum.status]?.class || 'bg-gray-100 text-gray-700',
                )
              "
            >
              {{ statusDisplayMap[datum.status]?.label || datum.status }}
            </span>
          </TableCell>
          <TableCell>Aksi</TableCell>
        </TableRow>
      </template>
      <template v-else>
        <TableRow>
          <TableCell :colspan="7" class="text-center py-8 text-gray-500">
            Belum ada data order produksi.
          </TableCell>
        </TableRow>
      </template>
    </TableBody>
  </Table>

  <div v-if="meta" class="flex justify-between items-center pt-4 w-full">
    <AppPagination :meta="meta" @update:page="emit('update:page', $event)" />
  </div>
</template>

<script setup lang="ts">
import type {
  PaginationMeta,
  SortField,
  SortOrder,
  SortParams,
} from '@/interfaces/getAll.interface';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Table, TableBody, TableCell, TableHead, TableRow, TableHeader } from '../ui/table';
import { cn } from '@/lib/utils';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';
import { Button } from '../ui/button';
import AppPagination from '../AppPagination.vue';
import { OrderStatus, type ProductionOrder } from '@/interfaces/productionOrder.interface';
import { formatDate } from '@/helper/formatDateHelper';

const props = defineProps<{
  pageTitle: string;
  data: ProductionOrder[];
  sortParams: SortParams;
  statusFilter: OrderStatus | null;
  isLoading: boolean;
  meta: PaginationMeta | null;
}>();

const emit = defineEmits<{
  (e: 'update:sort', params: SortParams): void;
  (e: 'update:filter', status: OrderStatus | null): void;
  (e: 'update:page', page: number | null): void;
}>();

const statusOptions: { value: OrderStatus | 'ALL'; label: string }[] = [
  { value: 'ALL', label: 'Semua' },
  { value: OrderStatus.WAITING, label: 'Menunggu' },
  { value: OrderStatus.IN_PROGRESS, label: 'Sedang Dikerjakan' },
  { value: OrderStatus.COMPLETED, label: 'Selesai' },
  { value: OrderStatus.CANCELED, label: 'Dibatalkan' },
];

const statusDisplayMap: Record<OrderStatus, { label: string; class: string }> = {
  [OrderStatus.WAITING]: {
    label: 'Menunggu',
    class: 'bg-gray-100 text-gray-700',
  },
  [OrderStatus.IN_PROGRESS]: {
    label: 'Sedang Dikerjakan',
    class: 'bg-yellow-100 text-yellow-700',
  },
  [OrderStatus.COMPLETED]: {
    label: 'Selesai',
    class: 'bg-green-100 text-green-700',
  },
  [OrderStatus.CANCELED]: {
    label: 'Dibatalkan',
    class: 'bg-red-100 text-red-700',
  },
};

const handleSort = (field: SortField) => {
  let newOrder: SortOrder | null = 'ASC';

  if (props.sortParams.field === field) {
    if (props.sortParams.order === 'ASC') {
      newOrder = 'DESC';
    } else if (props.sortParams.order === 'DESC') {
      newOrder = 'ASC';
    }
  }

  emit('update:sort', {
    field: newOrder ? field : null,
    order: newOrder,
  });
};

const onFilterChange = (value: OrderStatus | 'ALL' | null) => {
  if (value === 'ALL' || value === null) {
    emit('update:filter', null);
  } else {
    emit('update:filter', value);
  }
};
</script>
