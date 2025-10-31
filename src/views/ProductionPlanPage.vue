<template>
  <DataTable
    :page-title="'Rencana Produksi'"
    :data="plans"
    :sort-params="sortParams"
    :status-filter="statusFilter"
    :is-loading="isLoading"
    @update:sort="sortParams = $event"
    @update:filter="statusFilter = $event"
  />
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import type { SortParams } from '@/components/ProductionPlanTable.vue';
import DataTable from '@/components/ProductionPlanTable.vue';
import { getToken } from '@/helper/authHelper';
import type { PlanStatus, ProductionPlan } from '@/interfaces/productionPlan.interface';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const plans = ref<ProductionPlan[]>([]);
const isLoading = ref(false);

const sortParams = ref<SortParams>({ field: 'created_at', order: 'DESC' });
const statusFilter = ref<PlanStatus | null>(null);

const fetchData = async () => {
  const token = getToken();
  isLoading.value = true;

  try {
    const response = await axios.get(`${baseUrl}/production-plans`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params: {
        status: statusFilter.value,
        sortField: sortParams.value.field,
        sortOrder: sortParams.value.order,
      },
    });

    plans.value = response.data.data;
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
</script>
