<template>
  <ProductionPlanManagerTable
    :page-title="'Setujui Rencana Produksi'"
    :data="plans"
    :sort-params="sortParams"
    :is-loading="isLoading"
    :meta="meta"
    @update:sort="sortParams = $event"
    @update:page="currentPage = $event"
    @decline-plan="handleDecline"
    @approve-plan="handleApprove"
  />
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import ProductionPlanManagerTable from '@/components/production-plan/ProductionPlanManagerTable.vue';
import { getToken } from '@/helper/authHelper';
import type { PaginationMeta, SortParams } from '@/interfaces/getAll.interface';
import { PlanStatus, type ProductionPlan } from '@/interfaces/productionPlan.interface';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const plans = ref<ProductionPlan[]>([]);
const isLoading = ref(false);

const sortParams = ref<SortParams>({ field: 'created_at', order: 'DESC' });
const currentPage = ref<number | null>(1);
const meta = ref<PaginationMeta | null>(null);

const handleDecline = async (planId: number) => {
  const token = getToken();
  isLoading.value = true;

  try {
    await axios.put(
      `${baseUrl}/production-plans/${planId}/approve`,
      {
        status: PlanStatus.DECLINED,
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

const handleApprove = async (planId: number, deadline: string) => {
  const token = getToken();
  isLoading.value = true;

  try {
    await axios.put(
      `${baseUrl}/production-plans/${planId}/approve`,
      {
        status: PlanStatus.APPROVED,
        deadline,
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
watch(currentPage, fetchData);
</script>
