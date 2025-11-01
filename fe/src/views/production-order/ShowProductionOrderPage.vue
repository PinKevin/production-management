<template>
  <div v-if="isLoading" class="flex flex-col gap-3">
    <Skeleton class="h-10 w-full" />
    <Skeleton class="h-10 w-full" />
    <Skeleton class="h-10 w-full" />
    <Skeleton class="h-10 w-full" />
  </div>

  <div v-else-if="productionOrder" class="flex flex-col gap-2">
    <h2 class="mx-auto text-2xl font-bold">Lihat Rencana</h2>

    <h3 class="text-lg font-bold">Nama Produk</h3>
    <p class="text-base">{{ productionOrder?.product.name }}</p>

    <h3 class="text-lg font-bold">Jumlah Produk Direncanakan</h3>
    <p class="text-base">{{ productionOrder?.quantityPlanned }}</p>

    <template v-if="productionOrder?.status === OrderStatus.COMPLETED">
      <h3 class="text-lg font-bold">Jumlah Produk Aktual</h3>
      <p class="text-base">
        {{ productionOrder?.quantityActual }} ({{
          (productionOrder.quantityActual / productionOrder.quantityPlanned) * 100
        }}%)
      </p>

      <h3 class="text-lg font-bold">Jumlah Produk Reject</h3>
      <p class="text-base">
        {{ productionOrder?.quantityRejected }} ({{
          (productionOrder.quantityRejected / productionOrder.quantityPlanned) * 100
        }}%)
      </p>
    </template>

    <h3 class="text-lg font-bold">Status</h3>
    <p class="text-base">
      <span
        :class="
          cn(
            'font-semibold px-2 py-0.5 rounded-full',
            orderStatusDisplayMap[productionOrder.status]?.class || 'bg-gray-100 text-gray-700',
          )
        "
      >
        {{ orderStatusDisplayMap[productionOrder.status]?.label || productionOrder.status }}
      </span>
    </p>

    <template v-if="productionOrder?.deadline">
      <h3 class="text-lg font-bold">Deadline</h3>
      <p class="text-base">
        {{
          productionOrder.deadline
            ? format(new Date(productionOrder.deadline), 'dd MMMM yyyy', { locale: id })
            : '-'
        }}
      </p>
    </template>

    <h3 class="text-lg font-bold">Dibuat tanggal</h3>
    <p class="text-base">
      {{
        productionOrder.createdAt
          ? format(new Date(productionOrder.createdAt), 'dd MMMM yyyy', { locale: id })
          : '-'
      }}
    </p>

    <h3 class="text-lg font-bold">Log Produksi</h3>
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>No.</TableHead>
          <TableHead>Status Awal</TableHead>
          <TableHead>Status Akhir</TableHead>
          <TableHead>Catatan</TableHead>
          <TableHead>Tanggal Diubah</TableHead>
          <TableHead>Diubah Oleh </TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <template v-if="isLoading">
          <TableRow>
            <TableCell :colspan="5" class="text-center py-8 text-gray-500">
              Memuat data...
            </TableCell>
          </TableRow>
        </template>

        <template v-else-if="productionOrder.productionLogs!.length > 0">
          <TableRow v-for="(datum, index) in productionOrder.productionLogs!" :key="datum.id">
            <TableCell>{{ index + 1 }}.</TableCell>
            <TableCell>
              {{ datum.statusFrom ?? '-' }}
            </TableCell>
            <TableCell>{{ datum.statusTo }}</TableCell>
            <TableCell>
              {{ datum.notes ?? '-' }}
            </TableCell>
            <TableCell>{{ formatDate(datum.createdAt) }}</TableCell>
            <TableCell>
              <div class="flex flex-col">
                <p>{{ datum.user.name }}</p>
                <p>{{ datum.user.department }}</p>
                <p>{{ datum.user.role }}</p>
              </div>
            </TableCell>
          </TableRow>
        </template>
        <template v-else>
          <TableRow>
            <TableCell :colspan="5" class="text-center py-8 text-gray-500">
              Belum ada data log.
            </TableCell>
          </TableRow>
        </template>
      </TableBody>
    </Table>

    <Button variant="secondary" class="max-w-20 mt-5" @click="router.back()"> Kembali </Button>
  </div>
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { getToken } from '@/helper/authHelper';
import { formatDate } from '@/helper/formatDateHelper';
import { orderStatusDisplayMap } from '@/helper/statusDisplayHelper';
import { OrderStatus, type ProductionOrder } from '@/interfaces/productionOrder.interface';
import { cn } from '@/lib/utils';
import axios from 'axios';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const productionOrder = ref<ProductionOrder | null>(null);
const isLoading = ref(false);

const router = useRouter();
const route = useRoute();
const planId = route.params.id as string;

const fetchProductionOrder = async (id: string) => {
  const token = getToken();
  isLoading.value = true;

  try {
    const response = await axios.get(`${baseUrl}/production-orders/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    productionOrder.value = response.data.data;
  } catch (error: any) {
    const status = error.response.status;

    if (error.response) {
      if (status === 401) {
        console.error('Not authenticated');
      } else {
        console.error('Server error');
      }
    } else {
      console.error('Something happened');
    }
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  if (planId) {
    fetchProductionOrder(planId);
  }
});
</script>
