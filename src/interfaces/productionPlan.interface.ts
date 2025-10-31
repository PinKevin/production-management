export const enum PlanStatus {
  CREATED = 'CREATED',
  NEEDS_APPROVAL = 'NEEDS_APPROVAL',
  APPROVED = 'APPROVED',
  DECLINED = 'DECLINED',
}

export interface ProductionPlan {
  id: number;
  status: PlanStatus;
  deadline: string;
  quantity: number;
  notes: string;
  productId: number;
  createdAt: string;
}
