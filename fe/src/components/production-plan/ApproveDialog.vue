<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ title }}</DialogTitle>
        <DialogDescription>{{ description }}</DialogDescription>
      </DialogHeader>

      <div class="flex w-full flex-col gap-y-2">
        <Select
          @update:model-value="
            (v) => {
              if (!v) return;
              dateValue = today(getLocalTimeZone()).add({ days: Number(v) });
            }
          "
        >
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Pilih tanggal cepat..." />
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="item in items" :key="item.value" :value="item.value.toString()">
              {{ item.label }}
            </SelectItem>
          </SelectContent>
        </Select>

        <Calendar v-model="dateValue" class="border rounded-md my-2 mx-auto" />

        <div class="text-sm text-muted-foreground p-2">
          Tanggal terpilih:
          <span class="font-medium text-primary">
            {{ dateValue ? formatDate(dateValue.toString()) : 'Belum dipilih' }}
          </span>
        </div>
      </div>

      <DialogFooter>
        <Button type="button" variant="outline" @click="emit('update:open', false)">Batal</Button>
        <Button type="button" variant="default" @click="onConfirm" :disabled="!isDateSelected">
          Setuju
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '../ui/dialog';
import { getLocalTimeZone, today, type DateValue } from '@internationalized/date';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Calendar } from '../ui/calendar';
import { formatDate } from '@/helper/formatDateHelper';
import { Button } from '../ui/button';
import { format } from 'date-fns';

const props = defineProps<{
  open: boolean;
  title: string;
  description: string;
}>();

const emit = defineEmits<{
  (e: 'update:open', value: boolean): void;
  (e: 'confirm', deadline: string): void;
}>();

const items = [
  { value: 7, label: '1 minggu' },
  { value: 14, label: '2 minggu' },
  { value: 30, label: '1 bulan' },
];

const dateValue = ref<DateValue>();

watch(
  () => props.open,
  (newOpen) => {
    if (newOpen) {
      dateValue.value = undefined;
    }
  },
);

const isDateSelected = computed(() => !!dateValue.value);

const onConfirm = () => {
  if (!isDateSelected.value) return;

  const jsDate = dateValue.value!.toDate(getLocalTimeZone());
  const deadlineString = format(jsDate, 'yyyy-MM-dd');

  emit('confirm', deadlineString);
  emit('update:open', false);
};
</script>
