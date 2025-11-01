<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="flex flex-col max-h-[90vh]">
      <DialogHeader>
        <DialogTitle>Cetak Laporan Rencana Produksi</DialogTitle>
        <DialogDescription
          >Pilih periode laporan. Pilih tanggal untuk menentukan minggu/bulan laporan
        </DialogDescription>
      </DialogHeader>

      <FieldGroup class="flex flex-col gap-y-2 overflow-y-auto pr-4">
        <Field>
          <FieldLabel for="reportPeriod">Periode Laporan</FieldLabel>
          <Select v-model="reportPeriod">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Pilih periode (mingguan/bulanan)" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="item in periodOptions" :key="item.value" :value="item.value">
                {{ item.label }}
              </SelectItem>
            </SelectContent>
          </Select>
        </Field>

        <Field>
          <FieldLabel>Pilih tanggal</FieldLabel>

          <div class="flex justify-center mx-auto">
            <Calendar v-model="dateValue" class="border rounded-md my-2" />
          </div>

          <div class="text-sm text-muted-foreground p-2">
            Tanggal terpilih:
            <span class="font-medium text-primary">
              {{ dateValue ? formatDate(dateValue.toString()) : 'Belum dipilih' }}
            </span>
          </div>
        </Field>
      </FieldGroup>

      <DialogFooter>
        <Button type="button" variant="outline" @click="emit('update:open', false)">Batal</Button>
        <Button type="button" variant="default" @click="onConfirm"> Cetak Laporan </Button>
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
import { getLocalTimeZone, type DateValue } from '@internationalized/date';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Calendar } from '../ui/calendar';
import { formatDate } from '@/helper/formatDateHelper';
import { Button } from '../ui/button';
import { format } from 'date-fns';
import { Field, FieldGroup } from '../ui/field';
import FieldLabel from '../ui/field/FieldLabel.vue';

export type ReportOptions = {
  period: 'weekly' | 'monthly' | null;
  date: string | null;
};

const props = defineProps<{
  open: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:open', value: boolean): void;
  (e: 'confirm', options: ReportOptions): void;
}>();

const reportPeriod = ref<'weekly' | 'monthly' | null>(null);
const dateValue = ref<DateValue>();

const periodOptions = [
  { value: 'weekly', label: 'Laporan Mingguan' },
  { value: 'monthly', label: 'Laporan Bulanan' },
];

watch(
  () => props.open,
  (newOpen) => {
    if (newOpen) {
      reportPeriod.value = null;
      dateValue.value = undefined;
    }
  },
);

const onConfirm = () => {
  const deadlineString = dateValue.value
    ? format(dateValue.value.toDate(getLocalTimeZone()), 'yyyy-MM-dd')
    : null;

  emit('confirm', {
    period: reportPeriod.value,
    date: deadlineString,
  });
  emit('update:open', false);
};
</script>
