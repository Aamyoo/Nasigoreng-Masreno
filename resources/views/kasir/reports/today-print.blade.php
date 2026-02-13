<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: 'Courier New', monospace; padding: 16px; max-width: 800px; margin: 0 auto; }
        .no-print { margin-bottom: 16px; }
        @media print { .no-print { display: none !important; } }
        h1 { font-size: 18px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 12px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .summary { margin: 16px 0; padding: 12px; background: #f9f9f9; }
        .summary p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" onclick="window.print()" style="padding: 8px 16px; cursor: pointer;">Cetak Laporan</button>
        <button type="button" onclick="window.close()" style="padding: 8px 16px; cursor: pointer; margin-left: 8px;">Tutup</button>
    </div>
    <h1>NASI GORENG MAS RENO - Laporan Harian</h1>
    <p>Tanggal: {{ now()->format('d/m/Y') }}</p>
    <div class="summary">
        <p><strong>Total Transaksi:</strong> {{ $totalCount }}</p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p><strong>Rata-rata per Transaksi:</strong> Rp {{ number_format($avgValue, 0, ',', '.') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Waktu</th>
                <th>Kasir</th>
                <th>Mode</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>#{{ $t->id }}</td>
                <td>{{ $t->tanggal->format('H:i') }}</td>
                <td>{{ $t->user->nama_lengkap ?? '-' }}</td>
                <td>{{ $t->mode_pesanan }}</td>
                <td class="text-right">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top: 16px; font-size: 11px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
