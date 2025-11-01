<template>
  <ProductionOrderTable
    :page-title="'Order Produksi'"
    :data="plans"
    :sort-params="sortParams"
    :status-filter="statusFilter"
    :is-loading="isLoading"
    :meta="meta"
    :is-change-status-dialog-open="isChangeStatusDialogOpen"
    :is-complete-status-dialog-open="isCompleteStatusDialogOpen"
    :order-to-changed="orderToChanged"
    :action-type="actionType"
    :validation-errors="validationErrors"
    @open-dialog="handleOpenDialog"
    @confirm-status-change="handleConfirmProcessOrCancel"
    @confirm-complete="handleCompleteOrder"
    @update:change-status-dialog="isChangeStatusDialogOpen = $event"
    @update:complete-status-dialog="isCompleteStatusDialogOpen = $event"
    @update:sort="sortParams = $event"
    @update:filter="statusFilter = $event"
    @update:page="currentPage = $event"
  />
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import ProductionOrderTable from '@/components/production-order/ProductionOrderTable.vue';
import { getToken } from '@/helper/authHelper';
import type { PaginationMeta, SortParams } from '@/interfaces/getAll.interface';
import { OrderStatus, type ProductionOrder } from '@/interfaces/productionOrder.interface';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

export type OrderAction = 'process' | 'cancel' | 'complete';

const plans = ref<ProductionOrder[]>([]);
const isLoading = ref(false);

const sortParams = ref<SortParams>({ field: 'deadline', order: 'ASC' });
const statusFilter = ref<OrderStatus | null>(null);
const currentPage = ref<number | null>(1);
const meta = ref<PaginationMeta | null>(null);

const isChangeStatusDialogOpen = ref(false);
const isCompleteStatusDialogOpen = ref(false);
const orderToChanged = ref<ProductionOrder | null>(null);
const actionType = ref<OrderAction | null>(null);

const validationErrors = ref<{
  quantity_actual?: string[];
  quantity_rejected?: string[];
} | null>(null);

const fetchData = async () => {
  const token = getToken();
  isLoading.value = true;
  validationErrors.value = null;

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

const handleOpenDialog = (order: ProductionOrder, action: OrderAction) => {
  orderToChanged.value = order;
  actionType.value = action;
  validationErrors.value = null;

  if (action === 'complete') {
    isCompleteStatusDialogOpen.value = true;
  } else {
    isChangeStatusDialogOpen.value = true;
  }
};

const handleStatusChange = async (orderId: number, action: OrderAction, payload: object = {}) => {
  const token = getToken();
  isLoading.value = true;
  validationErrors.value = null;

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
        ...payload,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    await fetchData();
    if (action === 'complete') {
      isCompleteStatusDialogOpen.value = false;
    } else {
      isChangeStatusDialogOpen.value = false;
    }
  } catch (error: any) {
    const status = error.response.status;
    const data = error.response.data;

    if (error.response) {
      if (status === 422) {
        validationErrors.value = data.data;
        isLoading.value = false;
        return;
      }
      if (status === 401) {
        console.error('Not authenticated.');
      }
    } else if (error.request) {
      console.error('Cannot connect to server');
    } else {
      console.error('Something happened');
    }
  }

  isLoading.value = false;
};

const handleConfirmProcessOrCancel = () => {
  if (orderToChanged.value && actionType.value && actionType.value !== 'complete') {
    handleStatusChange(orderToChanged.value.id, actionType.value);
  }
};

const handleCompleteOrder = async (quantities: {
  quantityActual: number;
  quantityRejected: number;
}) => {
  if (orderToChanged.value) {
    const payload = {
      quantity_actual: quantities.quantityActual,
      quantity_rejected: quantities.quantityRejected,
    };

    handleStatusChange(orderToChanged.value?.id, 'complete', payload);
  }
};

onMounted(fetchData);

watch(sortParams, fetchData, { deep: true });
watch(statusFilter, fetchData);
watch(currentPage, fetchData);
</script>
