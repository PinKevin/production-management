import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export const formatDate = (dateString: string) => {
  try {
    return format(new Date(dateString), 'dd MMMM yyyy', { locale: id });
  } catch (e) {
    return 'Tanggal Invalid';
  }
};
