@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Transaksi</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
            <p class="text-2xl font-semibold text-gray-800">Rp. {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Jumlah Transaksi</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $totalTransactions ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tanggal</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No. Transaksi</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Kasir</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Total</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $transaction->tanggal->format('d M Y H:i') }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">#{{ $transaction->id }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $transaction->user->nama_lengkap ?? $transaction->user->username }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">Rp.
                            {{ number_format($transaction->total ?? 0, 0, ',', '.') }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $transaction->metode_pembayaran }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 text-center text-gray-500">
                            Tidak ada transaksi pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
