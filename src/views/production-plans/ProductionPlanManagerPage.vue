<template>
  <ProductionPlanTable
    :page-title="'Rencana Produksi'"
    :data="plans"
    :sort-params="sortParams"
    :status-filter="statusFilter"
    :is-loading="isLoading"
    :meta="meta"
    @update:sort="sortParams = $event"
    @update:filter="statusFilter = $event"
    @update:page="currentPage = $event"
  />
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import ProductionPlanTable from '@/components/production-plan/ProductionPlanTable.vue';
import { getToken } from '@/helper/authHelper';
import type { PaginationMeta, SortParams } from '@/interfaces/getAll.interface';
import type { PlanStatus, ProductionPlan } from '@/interfaces/productionPlan.interface';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const plans = ref<ProductionPlan[]>([]);
const isLoading = ref(false);

const sortParams = ref<SortParams>({ field: 'created_at', order: 'DESC' });
const statusFilter = ref<PlanStatus | null>(null);
const currentPage = ref<number | null>(1);
const meta = ref<PaginationMeta | null>(null);

const fetchData = async () => {
  const token = getToken();
  isLoading.value = true;

  try {
    const response = await axios.get(`${baseUrl}/approve/production-plans`, {
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

onMounted(fetchData);

watch(sortParams, fetchData, { deep: true });
watch(statusFilter, fetchData);
watch(currentPage, fetchData);
</script>
