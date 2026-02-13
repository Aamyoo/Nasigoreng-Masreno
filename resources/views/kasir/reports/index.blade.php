@extends('layouts.kasir')

@section('page-title', 'Laporan Kasir')
@section('page-description', 'Riwayat transaksi, cetak struk, dan ringkasan penjualan')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 p-6">
    {{-- Main Content - 70% --}}
    <div class="flex-1 min-w-0 lg:w-[70%]">
        {{-- Filters --}}
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-6">
            <form action="{{ route('kasir.reports') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label for="start_date" class="block text-gray-700 text-sm font-medium mb-1">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-gray-700 text-sm font-medium mb-1">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                </div>
                <div>
                    <label for="cashier_id" class="block text-gray-700 text-sm font-medium mb-1">Kasir</label>
                    <select id="cashier_id" name="cashier_id" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm min-w-[140px]">
                        <option value="">Semua Kasir</option>
                        @foreach($cashiers as $c)
                        <option value="{{ $c->id }}" {{ request('cashier_id') == $c->id ? 'selected' : '' }}>{{ $c->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="metode_pembayaran" class="block text-gray-700 text-sm font-medium mb-1">Metode Bayar</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm min-w-[140px]">
                        <option value="">Semua</option>
                        @foreach($paymentMethods as $pm)
                        <option value="{{ $pm }}" {{ request('metode_pembayaran') == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="transaction_id" class="block text-gray-700 text-sm font-medium mb-1">No. Transaksi</label>
                    <input type="number" id="transaction_id" name="transaction_id" value="{{ request('transaction_id') }}"
                        placeholder="ID" min="1" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm w-24">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        Filter
                    </button>
                    <a href="{{ route('kasir.reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm">Reset</a>
                    <a href="{{ route('kasir.reports.export', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg text-sm inline-flex items-center gap-1">
                        <i class="fas fa-file-export"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>

        {{-- Transaction Table --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kasir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Meja</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bayar</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Dibayar</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Kembalian</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" data-transaction-id="{{ $t->id }}" onclick="openDetailModal({{ $t->id }})">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $t->id }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->tanggal->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->user->nama_lengkap ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->mode_pesanan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->nomor_meja ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->metode_pembayaran }}</td>
                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-600">Rp {{ number_format($t->dibayar, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-600">Rp {{ number_format($t->kembalian, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center" onclick="event.stopPropagation();">
                            <a href="{{ route('kasir.reports.receipt', $t) }}" target="_blank" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                <i class="fas fa-receipt"></i> Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    {{-- Sidebar Quick Actions - 30% --}}
    <aside class="w-full lg:w-[30%] lg:max-w-sm space-y-4 flex-shrink-0">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fas fa-tachometer-alt text-primary-500"></i> Ringkasan Hari Ini
            </h3>
            <div class="space-y-2 text-sm">
                <p class="flex justify-between"><span class="text-gray-600">Transaksi</span><span class="font-semibold">{{ $dailySummary['total_transactions'] }}</span></p>
                <p class="flex justify-between"><span class="text-gray-600">Pendapatan</span><span class="font-semibold text-green-600">Rp {{ number_format($dailySummary['total_revenue'], 0, ',', '.') }}</span></p>
                <p class="flex justify-between"><span class="text-gray-600">Rata-rata</span><span class="font-semibold">Rp {{ number_format($dailySummary['avg_value'], 0, ',', '.') }}</span></p>
            </div>
        </div>

        <a href="{{ route('kasir.reports.today-print') }}" target="_blank" class="block w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-xl text-center text-sm transition-colors">
            <i class="fas fa-print mr-2"></i> Cetak Laporan Hari Ini
        </a>

        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fas fa-user-clock text-accent-500"></i> Shift Saya Hari Ini
            </h3>
            <div class="space-y-2 text-sm">
                <p class="flex justify-between"><span class="text-gray-600">Transaksi</span><span class="font-semibold">{{ $cashierShiftSummary['total_transactions'] }}</span></p>
                <p class="flex justify-between"><span class="text-gray-600">Total Penjualan</span><span class="font-semibold text-green-600">Rp {{ number_format($cashierShiftSummary['total_sales'], 0, ',', '.') }}</span></p>
            </div>
        </div>

        <button type="button" onclick="openStockAlertModal()" class="w-full bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-800 font-medium py-2.5 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Stock Alert
            @if($lowStockItems->count() > 0)
            <span class="bg-amber-500 text-white text-xs rounded-full px-2">{{ $lowStockItems->count() }}</span>
            @endif
        </button>

        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fas fa-fire text-orange-500"></i> Populer Hari Ini
            </h3>
            @if($popularItemsToday->count() > 0)
            <ul class="space-y-1.5 text-sm">
                @foreach($popularItemsToday as $item)
                <li class="flex justify-between">
                    <span class="text-gray-700 truncate">{{ $item->nama_menu }}</span>
                    <span class="text-gray-500 font-medium">{{ $item->total_qty }}x</span>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-500 text-sm">Belum ada data penjualan hari ini.</p>
            @endif
        </div>

        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Filter Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('kasir.reports', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="block w-full text-left py-2 px-3 rounded-lg hover:bg-white text-sm text-gray-700 border border-gray-200 hover:border-primary-300">Hari Ini</a>
                <a href="{{ route('kasir.reports', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="block w-full text-left py-2 px-3 rounded-lg hover:bg-white text-sm text-gray-700 border border-gray-200 hover:border-primary-300">Minggu Ini</a>
                <a href="{{ route('kasir.reports', array_merge(request()->query(), ['quick_filter' => 'high_value'])) }}" class="block w-full text-left py-2 px-3 rounded-lg hover:bg-white text-sm text-gray-700 border border-gray-200 hover:border-primary-300">Nilai Tinggi (&gt;100rb)</a>
            </div>
        </div>
    </aside>
</div>

{{-- Transaction Detail Modal --}}
<div id="detail-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeDetailModal()"></div>
        <div id="detail-modal-content" class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 overflow-y-auto max-h-[85vh]">
                <div id="detail-loading" class="text-center py-8 text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                    <p class="mt-2">Memuat detail...</p>
                </div>
                <div id="detail-body" class="hidden"></div>
            </div>
            <div class="p-4 bg-gray-50 border-t flex justify-end gap-2">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Tutup</button>
                <a id="detail-print-link" href="#" target="_blank" class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700">Cetak Struk</a>
            </div>
        </div>
    </div>
</div>

{{-- Stock Alert Modal --}}
<div id="stock-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeStockAlertModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-amber-500"></i> Stok Rendah (&lt;10)
                </h3>
                @if($lowStockItems->count() > 0)
                <ul class="space-y-2">
                    @foreach($lowStockItems as $m)
                    <li class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                        <span class="text-gray-700">{{ $m->nama_menu }}</span>
                        <span class="font-semibold {{ $m->stok < 5 ? 'text-red-600' : 'text-amber-600' }}">{{ $m->stok }} stok</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-gray-500">Tidak ada item dengan stok rendah.</p>
                @endif
            </div>
            <div class="p-4 bg-gray-50 border-t flex justify-end">
                <button type="button" onclick="closeStockAlertModal()" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const baseUrl = '{{ url("kasir/reports/transaction") }}';
    const receiptBaseUrl = '{{ url("kasir/reports/receipt") }}';

    window.openDetailModal = function(id) {
        const modal = document.getElementById('detail-modal');
        const loading = document.getElementById('detail-loading');
        const body = document.getElementById('detail-body');
        const printLink = document.getElementById('detail-print-link');
        modal.classList.remove('hidden');
        body.classList.add('hidden');
        body.innerHTML = '';
        loading.classList.remove('hidden');
        printLink.href = receiptBaseUrl + '/' + id;

        fetch(baseUrl + '/' + id, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            loading.classList.add('hidden');
            body.innerHTML = buildDetailHtml(data);
            body.classList.remove('hidden');
        })
        .catch(() => {
            loading.classList.add('hidden');
            body.innerHTML = '<p class="text-red-500">Gagal memuat detail transaksi.</p>';
            body.classList.remove('hidden');
        });
    };

    window.closeDetailModal = function() {
        document.getElementById('detail-modal').classList.add('hidden');
    };

    function buildDetailHtml(data) {
        let rows = data.details.map(d =>
            `<tr class="border-b border-gray-100">
                <td class="py-2 text-gray-700">${escapeHtml(d.menu)}</td>
                <td class="py-2 text-center">${d.qty}</td>
                <td class="py-2 text-right">Rp ${formatNum(d.harga)}</td>
                <td class="py-2 text-right font-medium">Rp ${formatNum(d.subtotal)}</td>
                <td class="py-2 text-sm text-gray-500">${d.catatan ? escapeHtml(d.catatan) : '-'}</td>
            </tr>`
        ).join('');
        return `
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Transaksi #${data.id}</h4>
            <div class="space-y-2 text-sm mb-4">
                <p><span class="text-gray-500">Tanggal</span> ${escapeHtml(data.tanggal)}</p>
                <p><span class="text-gray-500">Kasir</span> ${escapeHtml(data.cashier)}</p>
                <p><span class="text-gray-500">Mode</span> ${escapeHtml(data.mode_pesanan)} ${data.nomor_meja ? ' • Meja ' + escapeHtml(data.nomor_meja) : ''}</p>
                <p><span class="text-gray-500">Pembayaran</span> ${escapeHtml(data.metode_pembayaran)}</p>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-600 font-medium">
                        <th class="py-2">Menu</th>
                        <th class="py-2 text-center">Qty</th>
                        <th class="py-2 text-right">Harga</th>
                        <th class="py-2 text-right">Subtotal</th>
                        <th class="py-2">Catatan</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
            <div class="mt-4 pt-4 border-t space-y-1 text-sm">
                <p class="flex justify-between"><span>Subtotal</span> Rp ${formatNum(data.subtotal)}</p>
                <p class="flex justify-between"><span>Pajak (10%)</span> Rp ${formatNum(data.pajak)}</p>
                <p class="flex justify-between font-semibold"><span>Total</span> Rp ${formatNum(data.total)}</p>
                <p class="flex justify-between"><span>Dibayar</span> Rp ${formatNum(data.dibayar)}</p>
                <p class="flex justify-between"><span>Kembalian</span> Rp ${formatNum(data.kembalian)}</p>
            </div>
        `;
    }

    function escapeHtml(s) {
        if (!s) return '';
        const div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }
    function formatNum(n) { return Number(n).toLocaleString('id-ID'); }

    window.openStockAlertModal = function() {
        document.getElementById('stock-modal').classList.remove('hidden');
    };
    window.closeStockAlertModal = function() {
        document.getElementById('stock-modal').classList.add('hidden');
    };
})();
</script>
@endsection
