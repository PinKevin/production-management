<template>
  <div class="flex flex-row justify-between items-center">
    <h1 class="mb-4 text-2xl font-bold">{{ pageTitle }}</h1>

    <div class="flex flex-row gap-2">
      <Select
        :model-value="statusFilter ?? 'ALL'"
        @update:model-value="(v) => onFilterChange(v as PlanStatus | 'ALL')"
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

      <Button as-child>
        <RouterLink to="/production-plans/create"> + Tambah </RouterLink>
      </Button>
    </div>
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
        <TableHead>Status</TableHead>
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
            <span
              :class="
                cn(
                  'font-semibold px-2 py-0.5 rounded-full text-xs',
                  planStatusDisplayMap[datum.status]?.class || 'bg-gray-100 text-gray-700',
                )
              "
            >
              {{ planStatusDisplayMap[datum.status]?.label || datum.status }}
            </span>
          </TableCell>
          <TableCell>
            <div v-if="datum.status !== PlanStatus.APPROVED" class="flex flex-row gap-2">
              <Button
                v-if="datum.status === PlanStatus.CREATED"
                size="icon"
                @click="openSendDialog(datum, 'send')"
              >
                <Send class="text-white" />
              </Button>

              <Button
                v-if="datum.status === PlanStatus.NEEDS_APPROVAL"
                size="icon"
                @click="openSendDialog(datum, 'revert')"
              >
                <Undo2 class="text-white" />
              </Button>

              <Button variant="secondary" size="icon">
                <RouterLink :to="`/production-plans/${datum.id}/edit`">
                  <Edit2 />
                </RouterLink>
              </Button>

              <Button variant="destructive" size="icon" @click="openDeleteDialog(datum)">
                <Trash2 class="text-white" />
              </Button>
            </div>

            <div v-if="datum.status === PlanStatus.APPROVED">Rencana sudah disetujui</div>
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

  <DeleteDialog
    v-model:open="isDeleteDialogOpen"
    title="Konfirmasi Hapus Rencana"
    :description="`Yakin ingin menghapus rencana untuk produk '${planToDelete?.product.name}'? Aksi ini tidak dapat dibatalkan.`"
    @confirm="handleConfirmDelete"
  />

  <SendToApproveDialog
    v-model:open="isSendDialogOpen"
    :title="actionType === 'send' ? 'Konfirmasi Pengajuan' : 'Batalkan Pengajuan'"
    :description="`Yakin ingin ${
      actionType === 'send' ? 'mengajukan' : 'membatalkan'
    } rencana untuk produk '${planToSend?.product.name}'?`"
    @confirm="handleApproval"
  />
</template>

<script setup lang="ts">
import { PlanStatus, type ProductionPlan } from '@/interfaces/productionPlan.interface';
import type {
  PaginationMeta,
  SortField,
  SortOrder,
  SortParams,
} from '@/interfaces/getAll.interface';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Table, TableBody, TableCell, TableHead, TableRow, TableHeader } from '../ui/table';
import { cn } from '@/lib/utils';
import { ChevronDown, ChevronUp, Edit2, Send, Trash2, Undo2 } from 'lucide-vue-next';
import { Button } from '../ui/button';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';
import AppPagination from '../AppPagination.vue';
import { RouterLink } from 'vue-router';
import { planStatusDisplayMap } from '@/helper/statusDisplayHelper';
import { ref } from 'vue';
import DeleteDialog from '../crud/DeleteDialog.vue';
import SendToApproveDialog from './SendToApproveDialog.vue';

const props = defineProps<{
  pageTitle: string;
  data: ProductionPlan[];
  sortParams: SortParams;
  statusFilter: PlanStatus | null;
  isLoading: boolean;
  meta: PaginationMeta | null;
}>();

const emit = defineEmits<{
  (e: 'update:sort', params: SortParams): void;
  (e: 'update:filter', status: PlanStatus | null): void;
  (e: 'update:page', page: number | null): void;
  (e: 'delete', planId: number): void;
  (e: 'sendToApprove', planId: number): void;
  (e: 'revertToCreated', planId: number): void;
}>();

const isDeleteDialogOpen = ref(false);
const planToDelete = ref<ProductionPlan | null>(null);

const openDeleteDialog = (plan: ProductionPlan) => {
  planToDelete.value = plan;
  isDeleteDialogOpen.value = true;
};

const handleConfirmDelete = () => {
  if (planToDelete.value) {
    emit('delete', planToDelete.value.id);
  }
};

const isSendDialogOpen = ref(false);
const planToSend = ref<ProductionPlan | null>(null);
const actionType = ref<'send' | 'revert' | null>(null);

const openSendDialog = (plan: ProductionPlan, action: 'send' | 'revert') => {
  planToSend.value = plan;
  actionType.value = action;
  isSendDialogOpen.value = true;
};

const handleApproval = () => {
  if (planToSend.value && actionType.value === 'send') {
    emit('sendToApprove', planToSend.value.id);
  } else if (planToSend.value && actionType.value === 'revert') {
    emit('revertToCreated', planToSend.value.id);
  }
};

const statusOptions: { value: PlanStatus | 'ALL'; label: string }[] = [
  { value: 'ALL', label: 'Semua' },
  ...Object.entries(planStatusDisplayMap).map(([key, value]) => ({
    value: key as PlanStatus,
    label: value.label,
  })),
];

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

const onFilterChange = (value: PlanStatus | 'ALL' | null) => {
  if (value === 'ALL' || value === null) {
    emit('update:filter', null);
  } else {
    emit('update:filter', value);
  }
};

const formatDate = (dateString: string) => {
  try {
    return format(new Date(dateString), 'dd MMMM yyyy', { locale: id });
  } catch (e) {
    return 'Tanggal Invalid';
  }
};
</script>
