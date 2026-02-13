@extends('layouts.kasir')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi Saya</h1>
</div>

@include('partials.alert-success')

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Transaksi</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse(auth()->user()->transactions()->latest()->get() as $transaction)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $transaction->tanggal->format('d M Y H:i') }}</td>
                <td class="px-5 py-5 border-b border-gray-200 text-sm">#{{ $transaction->id }}</td>
                <td class="px-5 py-5 border-b border-gray-200 text-sm">Rp. {{ number_format($transaction->total ?? 0, 0, ',', '.') }}</td>
                <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $transaction->metode_pembayaran }}</td>
                <td class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                    <a href="{{ route('kasir.transaction.show', $transaction->id) }}" class="text-green-600 hover:text-green-900">Lihat Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-5 border-b border-gray-200 text-center text-gray-500">
                    Anda belum memiliki transaksi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection