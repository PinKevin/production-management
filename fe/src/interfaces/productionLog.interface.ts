import type { OrderStatus } from './productionOrder.interface';
import type { User } from './user.interface';

export interface ProductionLog {
  id: number;
  statusFrom: OrderStatus;
  statusTo: OrderStatus;
  user: User;
  notes?: string;
  createdAt: string;
}
