<template>
  <div v-if="isLoading" class="flex flex-col gap-3">
    <Skeleton class="h-10 w-full" />
    <Skeleton class="h-10 w-full" />
    <Skeleton class="h-10 w-full" />
    <Skeleton class="h-10 w-full" />
  </div>

  <div v-else-if="productionPlan" class="flex flex-col gap-2">
    <h2 class="mx-auto text-2xl font-bold">Lihat Rencana</h2>

    <h3 class="text-lg font-bold">Nama Produk</h3>
    <p class="text-base">{{ productionPlan?.product.name }}</p>

    <h3 class="text-lg font-bold">Jumlah Produk</h3>
    <p class="text-base">{{ productionPlan?.quantity }}</p>

    <h3 class="text-lg font-bold">Catatan</h3>
    <p class="text-base">{{ productionPlan?.notes || '-' }}</p>

    <h3 class="text-lg font-bold">Status</h3>
    <p class="text-base">
      <span
        :class="
          cn(
            'font-semibold px-2 py-0.5 rounded-full',
            planStatusDisplayMap[productionPlan.status]?.class || 'bg-gray-100 text-gray-700',
          )
        "
      >
        {{ planStatusDisplayMap[productionPlan.status]?.label || productionPlan.status }}
      </span>
    </p>

    <template v-if="productionPlan?.deadline">
      <h3 class="text-lg font-bold">Deadline</h3>
      <p class="text-base">
        {{
          productionPlan.deadline
            ? format(new Date(productionPlan.deadline), 'dd MMMM yyyy', { locale: id })
            : '-'
        }}
      </p>
    </template>

    <Button variant="secondary" class="max-w-20 mt-5" @click="router.back()"> Kembali </Button>
  </div>
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import { getToken } from '@/helper/authHelper';
import { planStatusDisplayMap } from '@/helper/statusDisplayHelper';
import type { ProductionPlan } from '@/interfaces/productionPlan.interface';
import { cn } from '@/lib/utils';
import axios from 'axios';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const productionPlan = ref<ProductionPlan | null>(null);
const isLoading = ref(false);

const router = useRouter();
const route = useRoute();
const planId = route.params.id as string;

const fetchProductionPlan = async (id: string) => {
  const token = getToken();
  isLoading.value = true;

  try {
    const response = await axios.get(`${baseUrl}/production-plans/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    productionPlan.value = response.data.data;
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
    fetchProductionPlan(planId);
  }
});
</script>
