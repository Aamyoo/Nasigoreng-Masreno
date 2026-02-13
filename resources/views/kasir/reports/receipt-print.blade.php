<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->id }} - Nasi Goreng Mas Reno</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.3; color: #000; background: #fff; padding: 8px; }
        .receipt { max-width: 80mm; margin: 0 auto; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 6px; margin-bottom: 6px; }
        .header { font-weight: bold; font-size: 14px; margin-bottom: 4px; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        .items { margin: 8px 0; }
        .item-row { display: flex; justify-content: space-between; margin: 2px 0; font-size: 11px; }
        .item-name { flex: 1; }
        .item-qty { width: 24px; text-align: center; }
        .item-price { width: 60px; text-align: right; }
        .footer { margin-top: 10px; text-align: center; font-size: 11px; }
        @media print {
            body { padding: 0; background: #fff; }
            .receipt { max-width: 80mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="receipt" id="receipt-content">
        <div class="header text-center border-bottom">NASI GORENG MAS RENO</div>
        <div class="border-bottom">
            <div class="row"><span>No. Struk</span><span>#{{ $transaction->id }}</span></div>
            <div class="row"><span>Tanggal</span><span>{{ $transaction->tanggal->format('d/m/Y H:i') }}</span></div>
            <div class="row"><span>Kasir</span><span>{{ $transaction->user->nama_lengkap ?? $transaction->user->username ?? '-' }}</span></div>
            <div class="row"><span>Mode</span><span>{{ $transaction->mode_pesanan }}</span></div>
            @if($transaction->nomor_meja)
            <div class="row"><span>Meja</span><span>{{ $transaction->nomor_meja }}</span></div>
            @endif
        </div>
        <div class="items border-bottom">
            <div class="row" style="font-weight: bold;">
                <span class="item-name">Menu</span>
                <span class="item-qty">Qty</span>
                <span class="item-price">Subtotal</span>
            </div>
            @foreach($transaction->details as $d)
            <div class="item-row">
                <span class="item-name">{{ $d->menu->nama_menu ?? '-' }}</span>
                <span class="item-qty">{{ $d->qty }}</span>
                <span class="item-price">{{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($d->catatan)
            <div class="item-row" style="font-size: 10px; padding-left: 8px;">Note: {{ $d->catatan }}</div>
            @endif
            @endforeach
        </div>
        <div>
            <div class="row"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span></div>
            <div class="row"><span>Pajak (10%)</span><span>Rp {{ number_format($transaction->pajak, 0, ',', '.') }}</span></div>
            <div class="row" style="font-weight: bold;"><span>Total</span><span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span></div>
            <div class="row"><span>Bayar</span><span>{{ $transaction->metode_pembayaran }}</span></div>
            <div class="row"><span>Dibayar</span><span>Rp {{ number_format($transaction->dibayar, 0, ',', '.') }}</span></div>
            <div class="row"><span>Kembalian</span><span>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</span></div>
        </div>
        <div class="footer border-bottom" style="border-top: 1px dashed #000; padding-top: 8px; margin-top: 8px;">
            Terima Kasih & Silakan Datang Kembali
        </div>
        <div class="text-center" style="font-size: 10px;">ID: {{ $transaction->id }}</div>
    </div>
    <div class="no-print" style="text-align: center; margin-top: 16px;">
        <button type="button" onclick="window.print()" style="padding: 8px 16px; cursor: pointer;">Cetak Struk</button>
        <button type="button" onclick="window.close()" style="padding: 8px 16px; cursor: pointer; margin-left: 8px;">Tutup</button>
    </div>
    <script>
        // Optional: auto-print when opened in popup
        if (window.opener) { window.onload = function() { window.print(); }; }
    </script>
</body>
</html>
