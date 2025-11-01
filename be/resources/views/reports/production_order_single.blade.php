<!DOCTYPE html>
<html>

    <head>
        <title>Laporan Order Produksi #{{ $order->id }}</title>
        <style>
            body {
                font-family: 'Helvetica', sans-serif;
                font-size: 12px;
            }

            .header h1 {
                font-size: 22px;
                text-align: center;
                margin-bottom: 5px;
            }

            .header p {
                font-size: 16px;
                text-align: center;
                margin: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #999;
                padding: 7px;
                text-align: left;
            }

            th {
                background-color: #f0f0f0;
                width: 25%;
            }

            h2 {
                font-size: 16px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 5px;
            }

            .logs th {
                background-color: #f9f9f9;
                width: auto;
            }

            .logs td {
                padding: 5px;
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
            <h1>Laporan Detail Order Produksi</h1>
            <p>Order ID: #{{ $order->id }} (Plan ID: #{{ $order->production_plan_id }})</p>
        </div>

        <h2>Detail Order</h2>
        <table>
            <tr>
                <th>Produk</th>
                <td>{{ $order->product ? $order->product->name : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Status Saat Ini</th>
                <td>{{ $order->status->value }}</td>
            </tr>
            <tr>
                <th>Deadline</th>
                <td>{{ $order->deadline ? Carbon\Carbon::parse($order->deadline)->format('d-m-Y \p\u\k\u\l 16:00') : '-' }}
                </td>
            </tr>
            <tr>
                <th>Qty Direncanakan</th>
                <td>{{ $order->quantity_planned }} pcs</td>
            </tr>
            <tr>
                <th>Qty Hasil Jadi</th>
                <td>{{ $order->quantity_actual ?? '-' }} pcs</td>
            </tr>
            <tr>
                <th>Qty Reject (NG)</th>
                <td>{{ $order->quantity_rejected ?? '-' }} pcs</td>
            </tr>
        </table>

        <h2>Riwayat / Log Produksi</h2>
        <table class="logs">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Status Dari</th>
                    <th>Status Ke</th>
                    <th>Oleh</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->productionLogs->sortBy('created_at') as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $log->status_from ?? '-' }}</td>
                        <td>{{ $log->status_to }}</td>
                        <td>{{ $log->user ? $log->user->name : 'Sistem' }}</td>
                        <td>{{ $log->notes }}</td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align: center;" colspan="5">Belum ada riwayat untuk order ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
        </div>
    </body>

</html>
