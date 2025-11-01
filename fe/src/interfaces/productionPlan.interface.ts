import type { Product } from './product.interface';

export const enum PlanStatus {
  CREATED = 'CREATED',
  NEEDS_APPROVAL = 'NEEDS_APPROVAL',
  APPROVED = 'APPROVED',
  DECLINED = 'DECLINED',
}

export interface ProductionPlan {
  id: number;
  status: PlanStatus;
  deadline: string | null;
  quantity: number;
  notes: string | null;
  productId: number;
  createdAt: string;
  product: Product;
}
