<template>
  <ProductionOrderTable
    :page-title="'Order Produksi'"
    :data="plans"
    :sort-params="sortParams"
    :status-filter="statusFilter"
    :is-loading="isLoading"
    :meta="meta"
    @update:sort="sortParams = $event"
    @update:filter="statusFilter = $event"
    @update:page="currentPage = $event"
    @order:process="handleStatusChange($event, 'process')"
    @order:cancel="handleStatusChange($event, 'cancel')"
  />
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import ProductionOrderTable, {
  type OrderAction,
} from '@/components/production-order/ProductionOrderTable.vue';
import { getToken } from '@/helper/authHelper';
import type { PaginationMeta, SortParams } from '@/interfaces/getAll.interface';
import { OrderStatus, type ProductionOrder } from '@/interfaces/productionOrder.interface';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const plans = ref<ProductionOrder[]>([]);
const isLoading = ref(false);

const sortParams = ref<SortParams>({ field: 'deadline', order: 'ASC' });
const statusFilter = ref<OrderStatus | null>(null);
const currentPage = ref<number | null>(1);
const meta = ref<PaginationMeta | null>(null);

const fetchData = async () => {
  const token = getToken();
  isLoading.value = true;

  try {
    const response = await axios.get(`${baseUrl}/production-orders`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params: {
        'page': currentPage.value,
        'status': statusFilter.value,
        'sort-field': sortParams.value.field,
        'sort-order': sortParams.value.order,
      },
    });

    plans.value = response.data.data;
    meta.value = response.data.meta;
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

const handleStatusChange = async (orderId: number, action: OrderAction) => {
  const token = getToken();
  isLoading.value = true;

  let status;
  if (action === 'process') {
    status = OrderStatus.IN_PROGRESS;
  } else if (action === 'complete') {
    status = OrderStatus.COMPLETED;
  } else {
    status = OrderStatus.CANCELED;
  }

  try {
    await axios.put(
      `${baseUrl}/production-orders/${orderId}/change-status`,
      {
        status,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    await fetchData();
  } catch (error: any) {
    const status = error.response.status;

    if (error.response) {
      if (status === 401) {
        console.error('Not authenticated.');
      }
    } else if (error.request) {
      console.error('Cannot connect to server');
    } else {
      console.error('Something happened');
    }
  } finally {
    isLoading.value = false;
  }
};
onMounted(fetchData);

watch(sortParams, fetchData, { deep: true });
watch(statusFilter, fetchData);
watch(currentPage, fetchData);
</script>
