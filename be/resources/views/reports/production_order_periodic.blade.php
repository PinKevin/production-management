<!DOCTYPE html>
<html>

    <head>
        <title>Laporan Order Produksi Periodik</title>
        <style>
            body {
                font-family: 'Helvetica', sans-serif;
                font-size: 11px;
            }

            .header h1 {
                font-size: 20px;
                text-align: center;
                margin-bottom: 0;
            }

            .header p {
                font-size: 14px;
                text-align: center;
                margin: 5px 0 20px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #999;
                padding: 6px;
                text-align: center;
            }

            th {
                background-color: #f0f0f0;
            }

            .text-center {
                text-align: center;
            }

            .footer {
                position: fixed;
                bottom: 0;
                right: 0;
                font-size: 10px;
                color: #888;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h1>Laporan Order Produksi</h1>
            <p>Periode: {{ $startDateFormatted }} s/d {{ $endDateFormatted }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>ID Plan</th>
                    <th>Produk</th>
                    <th>Status</th>
                    <th>Qty Direncanakan</th>
                    <th>Qty Jadi</th>
                    <th>Qty Reject</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $loop->index + 1 }}.</td>
                        <td>{{ $order->production_plan_id }}</td>
                        <td>{{ $order->product ? $order->product->name : 'N/A' }}</td>
                        <td>{{ $order->status->value }}</td>
                        <td class="text-center">{{ $order->quantity_planned }}</td>
                        <td class="text-center">{{ $order->quantity_actual ?? 0 }}</td>
                        <td class="text-center">{{ $order->quantity_rejected ?? 0 }}</td>
                        <td>{{ $order->deadline ? Carbon\Carbon::parse($order->deadline)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="8">Tidak ada data order produksi untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
        </div>
    </body>

</html>
