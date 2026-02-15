<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            margin: 0;
        }

        #receipt-container {
            background-color: white;
            width: 380px;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .info {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info p {
            margin: 5px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .items-table th,
        .items-table td {
            text-align: left;
            padding: 4px 0;
        }

        .items-table .item-name {
            width: 50%;
        }

        .items-table .item-qty {
            text-align: center;
            width: 15%;
        }

        .items-table .item-price {
            text-align: right;
            width: 35%;
        }

        .totals {
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 14px;
        }

        .totals p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }

        .totals .total-final {
            font-weight: bold;
            font-size: 16px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            border-top: 2px dashed #000;
            padding-top: 10px;
        }

        .actions {
            text-align: center;
        }

        .actions button,
        .actions a {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .actions .print-btn {
            background-color: #28a745;
            color: white;
        }

        .actions .back-btn {
            background-color: #6c757d;
            color: white;
        }

        /* CSS Khusus untuk Mencetak */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }

            #receipt-container {
                box-shadow: none;
                border: none;
                width: 100%;
                margin: 0;
            }

            .actions {
                display: none;
                /* Sembunyikan tombol saat print */
            }
        }
    </style>
</head>

<body>

    <div id="receipt-container">
        <div class="header">
            <h1>Nasi Goreng Mas Reno</h1>
            <p>Jl. Raya No. 123, Jakarta</p>
            <p>Telp: 021-12345678</p>
        </div>

        <div class="info">
            <p><strong>No. Transaksi:</strong> #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Tanggal:</strong> {{ $transaction->tanggal->format('d M Y H:i:s') }}</p>
            <p><strong>Kasir:</strong> {{ $transaction->user->nama_lengkap ?? $transaction->user->username }}</p>
            <p><strong>Mode:</strong> {{ $transaction->mode_pesanan }}</p>
            @if ($transaction->nomor_meja)
                <p><strong>Meja:</strong> {{ $transaction->nomor_meja }}</p>
            @endif
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="item-name">Menu</th>
                    <th class="item-qty">Qty</th>
                    <th class="item-price">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td class="item-name">{{ $detail->menu->nama_menu }}</td>
                        <td class="item-qty">{{ $detail->qty }}</td>
                        <td class="item-price">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p><span>Subtotal:</span> <span>Rp. {{ number_format($transaction->subtotal, 0, ',', '.') }}</span></p>
            <p><span>Pajak (10%):</span> <span>Rp. {{ number_format($transaction->pajak, 0, ',', '.') }}</span></p>
            <p class="total-final"><span>TOTAL:</span> <span>Rp.
                    {{ number_format($transaction->total, 0, ',', '.') }}</span></p>
            <p><span>Metode Bayar:</span> <span>{{ $transaction->metode_pembayaran }}</span></p>
            @if ($transaction->metode_pembayaran === 'QRIS' && $transaction->midtrans_qr_url)
                <p><span>QR URL:</span> <span style="word-break: break-all;">{{ $transaction->midtrans_qr_url }}</span></p>
            @endif
            <p><span>Dibayar:</span> <span>Rp. {{ number_format($transaction->dibayar, 0, ',', '.') }}</span></p>
            <p><span>Kembalian:</span> <span>Rp. {{ number_format($transaction->kembalian, 0, ',', '.') }}</span></p>
        </div>

        <div class="footer">
            <p>================================</p>
            <p>Terima Kasih</p>
            <p>Selamat Menikmati</p>
        </div>
    </div>

    <div class="actions">
        <button class="print-btn" onclick="window.print()">🖨️ Cetak Struk</button>
        <a href="{{ route('kasir.transaction.create') }}" class="back-btn">⬅️ Kembali ke Transaksi</a>
    </div>

</body>

</html>
