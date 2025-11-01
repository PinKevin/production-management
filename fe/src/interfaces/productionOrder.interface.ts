import type { Product } from './product.interface';
import type { ProductionLog } from './productionLog.interface';

export const enum OrderStatus {
  WAITING = 'WAITING',
  IN_PROGRESS = 'IN_PROGRESS',
  COMPLETED = 'COMPLETED',
  CANCELED = 'CANCELED',
}

export interface ProductionOrder {
  id: number;
  productId: number;
  quantityPlanned: number;
  quantityActual: number;
  quantityRejected: number;
  status: OrderStatus;
  deadline: string;
  createdAt: string;
  product: Product;
  productionLogs?: ProductionLog[];
}
