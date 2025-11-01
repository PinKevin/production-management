<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ title }}</DialogTitle>
        <DialogDescription>{{ description }}</DialogDescription>
      </DialogHeader>

      <FieldGroup class="flex w-full flex-col gap-y-2">
        <Field>
          <FieldLabel for="quantityActual">Jumlah Aktual</FieldLabel>
          <Input
            id="quantityActual"
            type="number"
            placeholder="Jumlah barang produksi"
            v-model.number="quantityActual"
            required
          />
          <div v-if="quantityActualError" class="text-sm text-red-600 mt-1">
            {{ quantityActualError }}
          </div>
        </Field>

        <Field>
          <FieldLabel for="quantityRejected">Jumlah Reject</FieldLabel>
          <Input
            id="quantityRejected"
            type="number"
            placeholder="Jumlah barang produksi"
            v-model.number="quantityRejected"
            required
          />
          <div v-if="quantityRejectedError" class="text-sm text-red-600 mt-1">
            {{ quantityRejectedError }}
          </div>
        </Field>
      </FieldGroup>

      <DialogFooter>
        <Button type="button" variant="outline" @click="emit('update:open', false)">Batal</Button>
        <Button type="button" variant="default" @click="onConfirm"> Setuju </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '../ui/dialog';
import { Button } from '../ui/button';
import { Field, FieldGroup, FieldLabel } from '../ui/field';
import { Input } from '../ui/input';

const props = defineProps<{
  open: boolean;
  title: string;
  description: string;
  quantityActualError?: string;
  quantityRejectedError?: string;
}>();

const emit = defineEmits<{
  (e: 'confirm', values: { quantityActual: number; quantityRejected: number }): void;
  (e: 'update:open', value: boolean): void;
}>();

const quantityActual = ref<number>();
const quantityRejected = ref<number>();

watch(
  () => props.open,
  (newOpen) => {
    if (newOpen) {
      quantityActual.value = 0;
      quantityRejected.value = 0;
    }
  },
);

const onConfirm = () => {
  emit('confirm', {
    quantityActual: quantityActual.value ?? 0,
    quantityRejected: quantityRejected.value ?? 0,
  });
};
</script>
