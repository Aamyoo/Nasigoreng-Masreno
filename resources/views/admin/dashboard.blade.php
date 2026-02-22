@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen">

        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
            <div class="bg-white p-4 rounded-2xl shadow-sm mt-4">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">

                    <div>
                        <label class="text-sm text-gray-600">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDateInput }}"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDateInput }}"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        Filter
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg text-sm">
                        Reset
                    </a>

                </form>
            </div>
            <p class="text-gray-500">Panel Admin Nasi Goreng Mas Reno</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Revenue -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <p class="text-gray-500 text-sm">Total Pendapatan</p>
                <h2 class="text-3xl font-bold mt-2">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>

            <!-- Transaction -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <p class="text-gray-500 text-sm">Total Transaksi</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalTransactions }}
                </h2>
            </div>

            <!-- Product -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <p class="text-gray-500 text-sm">Jumlah Produk</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalProducts }}
                </h2>
            </div>

        </div>

        <!-- Chart + Recent -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h2 class="font-semibold mb-4">Pendapatan</h2>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Recent Transaction -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h2 class="font-semibold mb-4">Transaksi Terbaru</h2>

                @forelse($recentTransactions as $transaction)
                    <div class="flex justify-between border-b py-2">
                        <div>
                            <p class="font-medium">
                                {{ $transaction->user->nama_lengkap ?? $transaction->user->username }}
                            </p>
                            <p class="text-sm text-gray-400">
                                {{ $transaction->tanggal->format('d M Y H:i') }}
                            </p>
                        </div>

                        <p class="font-semibold text-green-600">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-400">Belum ada transaksi</p>
                @endforelse

            </div>

        </div>

    </div>

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('revenueChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chartTotals) !!},
                    tension: 0.4
                }]
            }
        });
    </script>
@endsection
