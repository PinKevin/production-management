<template>
  <div class="flex flex-row justify-between items-center">
    <h1 class="mb-4 text-2xl font-bold">{{ pageTitle }}</h1>
  </div>

  <Table>
    <TableHeader>
      <TableRow>
        <TableHead>No.</TableHead>
        <TableHead>Produk</TableHead>
        <TableHead>Jumlah</TableHead>
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
        <TableHead>Aksi</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <template v-if="isLoading">
        <TableRow>
          <TableCell :colspan="6" class="text-center py-8 text-gray-500">
            Memuat data...
          </TableCell>
        </TableRow>
      </template>

      <template v-else-if="data.length > 0">
        <TableRow v-for="(datum, index) in data" :key="datum.id">
          <TableCell>{{ meta ? meta.from + index : index + 1 }}.</TableCell>
          <TableCell>
            <RouterLink :to="`/production-plans/${datum.id}`">
              {{ datum.product.name }}
            </RouterLink>
          </TableCell>
          <TableCell>{{ datum.quantity }}</TableCell>
          <TableCell>{{ formatDate(datum.createdAt) }}</TableCell>
          <TableCell>
            <div class="flex flex-row gap-2">
              <Button size="icon" variant="destructive" @click="openDeclineDialog(datum)">
                <X />
              </Button>
              <Button size="icon" @click="openApprovalDialog(datum)">
                <Check />
              </Button>
            </div>
          </TableCell>
        </TableRow>
      </template>
      <template v-else>
        <TableRow>
          <TableCell :colspan="6" class="text-center py-8 text-gray-500">
            Belum ada data rencana produksi.
          </TableCell>
        </TableRow>
      </template>
    </TableBody>
  </Table>

  <div v-if="meta" class="flex justify-between items-center pt-4 w-full">
    <AppPagination :meta="meta" @update:page="emit('update:page', $event)" />
  </div>

  <ConfirmDialog
    v-model:open="isDeclineDialogOpen"
    title="Konfirmasi Tolak Rencana"
    :description="`Yakin ingin menolak rencana untuk produk '${planToDecline?.product.name}'`"
    :is-destructive="true"
    @confirm="handleConfirmDecline"
  />

  <ApproveDialog
    v-model:open="isApprovalDialogOpen"
    title="Setujui dan Tentukan Deadline"
    description="Silakan pilih tanggal deadline untuk rencana produksi ini."
    @confirm="handleConfirmApprove"
  />
</template>

<script setup lang="ts">
import { type ProductionPlan } from '@/interfaces/productionPlan.interface';
import type {
  PaginationMeta,
  SortField,
  SortOrder,
  SortParams,
} from '@/interfaces/getAll.interface';
import { Table, TableBody, TableCell, TableHead, TableRow, TableHeader } from '../ui/table';
import { cn } from '@/lib/utils';
import { Check, ChevronDown, ChevronUp, X } from 'lucide-vue-next';
import { Button } from '../ui/button';
import AppPagination from '../AppPagination.vue';
import { RouterLink } from 'vue-router';
import { ref } from 'vue';
import ApproveDialog from './ApproveDialog.vue';
import { formatDate } from '@/helper/formatDateHelper';
import ConfirmDialog from '../crud/ConfirmDialog.vue';

const props = defineProps<{
  pageTitle: string;
  data: ProductionPlan[];
  sortParams: SortParams;
  isLoading: boolean;
  meta: PaginationMeta | null;
}>();

const emit = defineEmits<{
  (e: 'update:sort', params: SortParams): void;
  (e: 'update:page', page: number | null): void;
  (e: 'delete', planId: number): void;
  (e: 'approvePlan', planId: number, deadline: string): void;
  (e: 'declinePlan', planId: number): void;
}>();

const isDeclineDialogOpen = ref(false);
const planToDecline = ref<ProductionPlan | null>(null);

const openDeclineDialog = (plan: ProductionPlan) => {
  planToDecline.value = plan;
  isDeclineDialogOpen.value = true;
};

const handleConfirmDecline = () => {
  if (planToDecline.value) {
    emit('declinePlan', planToDecline.value.id);
  }
};

const isApprovalDialogOpen = ref(false);
const planToApproved = ref<ProductionPlan | null>(null);

const openApprovalDialog = (plan: ProductionPlan) => {
  planToApproved.value = plan;
  isApprovalDialogOpen.value = true;
};

const handleConfirmApprove = (deadline: string) => {
  if (planToApproved.value) {
    emit('approvePlan', planToApproved.value.id, deadline);
  }
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
</script>
