<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Rencana Produksi</title>
        <style>
            body {
                font-family: 'Times New Roman', serif;
                font-size: 12px;
                color: #333;
            }

            .container {
                width: 100%;
                margin: 0 auto;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .header h1 {
                margin: 0;
                font-size: 24px;
            }

            .header p {
                margin: 5px 0;
                font-size: 14px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .footer {
                text-align: right;
                font-size: 10px;
                color: #888;
                position: fixed;
                /* Agar muncul di setiap halaman */
                bottom: 0;
                right: 0;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>Laporan Rencana Produksi</h1>
                <p>Periode: {{ $startDateFormatted }} s/d {{ $endDateFormatted }}</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Produk</th>
                        <th>Jumlah (Qty)</th>
                        <th>Status</th>
                        <th>Tgl. Dibuat</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        <tr>
                            <td>{{ $loop->index + 1 }}.</td>
                            <td>{{ $plan->product ? $plan->product->name : 'N/A' }}</td>
                            <td>{{ $plan->quantity }}</td>
                            <td>{{ $plan->status->value }}</td>
                            <td>{{ $plan->created_at->format('d-m-Y') }}</td>
                            <td>{{ $plan->deadline ? Carbon\Carbon::parse($plan->deadline)->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td style="text-align: center;" colspan="6">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer">
                Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
            </div>
        </div>
    </body>

</html>
