@extends('layouts.kasir')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi #{{ $transaction->id }}</h1>
        <button onclick="window.print()"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            <i class="fas fa-print mr-2"></i> Cetak
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-4">Informasi Transaksi</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Tanggal:</span> {{ $transaction->tanggal->format('d M Y H:i') }}
                </div>
                <div>
                    <span class="font-medium">Kasir:</span>
                    {{ $transaction->user->nama_lengkap ?? $transaction->user->username }}
                </div>
                <div>
                    <span class="font-medium">Metode Pembayaran:</span> {{ $transaction->metode_pembayaran }}
                </div>
                <div>
                    <span class="font-medium">Mode Pesanan:</span> {{ $transaction->mode_pesanan }}
                </div>
                @if ($transaction->metode_pembayaran === 'QRIS' && $transaction->midtrans_qr_url)
                    <div class="col-span-2">
                        <span class="font-medium">QR URL:</span>
                        <a href="{{ $transaction->midtrans_qr_url }}" target="_blank" class="text-blue-600 underline break-all">
                            {{ $transaction->midtrans_qr_url }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t pt-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Detail Pesanan</h2>
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Menu</th>
                        <th class="text-center py-2">Qty</th>
                        <th class="text-right py-2">Harga</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $detail)
                        <tr class="border-b">
                            <td class="py-2">{{ $detail->menu->nama_menu }}</td>
                            <td class="text-center py-2">{{ $detail->qty }}</td>
                            <td class="text-right py-2">Rp. {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-right py-2">Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-semibold pt-4">Subtotal:</td>
                        <td class="text-right font-semibold pt-4">Rp.
                            {{ number_format($transaction->subtotal ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right font-semibold">Pajak (10%):</td>
                        <td class="text-right font-semibold">Rp. {{ number_format($transaction->pajak ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right font-bold text-lg">Total:</td>
                        <td class="text-right font-bold text-lg">Rp.
                            {{ number_format($transaction->total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right font-semibold">Dibayar:</td>
                        <td class="text-right font-semibold">Rp.
                            {{ number_format($transaction->dibayar ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right font-semibold">Kembalian:</td>
                        <td class="text-right font-semibold">Rp.
                            {{ number_format($transaction->kembalian ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
