<template>
  <h2 class="mx-auto text-2xl font-bold">Tambah Rencana</h2>
  <form @submit.prevent="handleSubmit">
    <FieldGroup>
      <Field>
        <FieldLabel for="product">Produk</FieldLabel>
        <Select :disabled="isLoading" v-model="selectedProductId">
          <SelectTrigger>
            <SelectValue placeholder="Ex: Selang oksigen"> </SelectValue>
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="product in products" :key="product.id" :value="product.id">
              {{ product.name }}
            </SelectItem>
          </SelectContent>
        </Select>
        <div v-if="productIdError" class="text-sm text-red-600 mt-1">
          {{ productIdError }}
        </div>
      </Field>

      <Field>
        <FieldLabel for="quantity">Jumlah</FieldLabel>
        <Input
          id="quantity"
          type="number"
          placeholder="Jumlah barang"
          v-model.number="quantity"
          :disabled="isLoading"
        />
        <div v-if="quantityError" class="text-sm text-red-600 mt-1">
          {{ quantityError }}
        </div>
      </Field>

      <Field>
        <FieldLabel for="notes">Catatan Tambahan</FieldLabel>
        <Textarea
          id="notes"
          name="notes"
          placeholder="Catatan (opsional)"
          v-model="notes"
          :disabled="isLoading"
        />
        <div v-if="notesError" class="text-sm text-red-600 mt-1">
          {{ notesError }}
        </div>
      </Field>

      <Field>
        <Button type="submit">Tambah</Button>
      </Field>
    </FieldGroup>
  </form>

  <Button as-child variant="outline" class="mt-4">
    <RouterLink to="/production-plans" class="text-lg mt-3"> Kembali </RouterLink>
  </Button>
</template>

<script setup lang="ts">
import { baseUrl } from '@/api/baseUrl';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { getToken } from '@/helper/authHelper';
import type { Product } from '@/interfaces/product.interface';
import { PlanStatus } from '@/interfaces/productionPlan.interface';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const products = ref<Product[]>([]);
const isLoading = ref(false);
const router = useRouter();

const selectedProductId = ref<number>();
const quantity = ref<number>();
const notes = ref<string>('');

const productIdError = ref('');
const quantityError = ref('');
const notesError = ref('');

const fetchProducts = async () => {
  const token = getToken();
  isLoading.value = true;

  try {
    const response = await axios.get(`${baseUrl}/products`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    products.value = response.data.data;
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

onMounted(fetchProducts);

const handleSubmit = async () => {
  const token = getToken();
  isLoading.value = true;

  productIdError.value = '';
  quantityError.value = '';
  notesError.value = '';

  try {
    await axios.post(
      `${baseUrl}/production-plans`,
      {
        product_id: selectedProductId.value,
        quantity: quantity.value,
        notes: notes.value,
        status: PlanStatus.CREATED,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    router.push('/production-plans');
  } catch (error: any) {
    const status = error.response.status;
    const data = error.response.data;

    if (error.response) {
      if (status === 422) {
        productIdError.value = data.data.product_id?.[0] || '';
        quantityError.value = data.data.quantity?.[0] || '';
        notesError.value = data.data.notes?.[0] || '';
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
</script>
