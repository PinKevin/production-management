import { PlanStatus } from '@/interfaces/productionPlan.interface';

export const planStatusDisplayMap: Record<PlanStatus, { label: string; class: string }> = {
  [PlanStatus.CREATED]: {
    label: 'Dibuat',
    class: 'bg-gray-100 text-gray-700',
  },
  [PlanStatus.NEEDS_APPROVAL]: {
    label: 'Menunggu Persetujuan',
    class: 'bg-yellow-100 text-yellow-700',
  },
  [PlanStatus.APPROVED]: {
    label: 'Disetujui',
    class: 'bg-green-100 text-green-700',
  },
  [PlanStatus.DECLINED]: {
    label: 'Ditolak',
    class: 'bg-red-100 text-red-700',
  },
};
