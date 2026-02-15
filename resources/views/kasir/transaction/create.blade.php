@extends('layouts.kasir')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Transaksi Baru</h1>
        <p class="text-gray-600">Buat transaksi baru untuk pelanggan</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Pilih Menu</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($menus as $menu)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer menu-item"
                                data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->nama_menu }}"
                                data-menu-price="{{ $menu->harga }}">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                                        class="w-16 h-16 object-cover rounded mr-4">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $menu->nama_menu }}</h3>
                                        <p class="text-gray-600">Rp. {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">Stok: {{ $menu->stok }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Detail Transaksi</h2>
                </div>
                <div class="p-6">
                    <form id="transaction-form">
                        <!-- Order Type -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Pesanan</label>
                            <select id="mode_pesanan" name="mode_pesanan"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Take Away">Take Away</option>
                                <option value="Dine In">Dine In</option>
                            </select>
                        </div>

                        <!-- Table Number (only visible when Dine In is selected) -->
                        <div id="table-number-container" class="mb-4 hidden">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Meja</label>
                            <input type="text" id="nomor_meja" name="nomor_meja"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Contoh: A1">
                        </div>

                        <!-- Cart Items -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-800 mb-2">Keranjang</h3>
                            <div id="cart-items" class="space-y-2">
                                <p class="text-gray-500 text-sm">Belum ada item yang dipilih</p>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-semibold">Rp. 0</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Pajak (10%):</span>
                                <span id="tax" class="font-semibold">Rp. 0</span>
                            </div>
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-800 font-bold">Total:</span>
                                <span id="total" class="font-bold text-lg">Rp. 0</span>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran</label>
                                <select id="metode_pembayaran" name="metode_pembayaran"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="Tunai">Tunai</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    {{-- <option value="E-Wallet">E-Wallet</option> --}}
                                </select>
                            </div>

                            {{-- @@ -74,50 +74,77 @@
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 lead --}}
                            <div class="flex
                            justify-between mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-semibold">Rp. 0</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Pajak (10%):</span>
                                <span id="tax" class="font-semibold">Rp. 0</span>
                            </div>
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-800 font-bold">Total:</span>
                                <span id="total" class="font-bold text-lg">Rp. 0</span>
                            </div>

                            <!-- Payment Method -->
                            {{-- <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran</label>
                                <select id="metode_pembayaran" name="metode_pembayaran"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="Tunai">Tunai</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </div> --}}

                            <div id="non-cash-info"
                                class="hidden mb-4 rounded-lg border border-indigo-200 bg-indigo-50 p-3">
                                <h4 class="text-sm font-semibold text-indigo-900">Informasi Pembayaran Non-Tunai</h4>

                                <div id="qris-info" class="hidden mt-3">
                                    <p class="text-xs text-indigo-700 mb-2">Scan QR dummy berikut untuk menyelesaikan
                                        transaksi
                                        QRIS:</p>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=NASIGORENG-MASRENO-QRIS-DUMMY"
                                        alt="QRIS Dummy" class="w-44 h-44 border rounded bg-white p-2 mx-auto">
                                    <p class="text-xs text-indigo-700 mt-2 text-center">Ref: QRIS-NM-001 (Dummy)</p>
                                </div>

                                <div id="bank-transfer-info" class="hidden mt-3 space-y-2">
                                    <p class="text-xs text-indigo-700">Transfer ke rekening dummy berikut:</p>
                                    <div class="text-sm text-indigo-900 bg-white rounded border p-2">
                                        <p><span class="font-semibold">Bank:</span> Bank Nasigoreng</p>
                                        <p><span class="font-semibold">No. Rekening:</span> 1234567890</p>
                                        <p><span class="font-semibold">Nama:</span> PT Nasigoreng Masreno</p>
                                    </div>
                                    <div class="text-sm text-indigo-900 bg-white rounded border p-2">
                                        <p><span class="font-semibold">Virtual Account:</span> 8808123456789012</p>
                                        <p><span class="font-semibold">Provider:</span> VA Dummy Midtrans</p>
                                    </div>
                                </div>

                                <p class="text-xs text-indigo-700 mt-3">Untuk mode demo, transaksi bisa langsung diproses.
                                </p>
                            </div>



                            <!-- Amount Paid -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Dibayar</label>
                                <input type="number" id="dibayar" name="dibayar"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="0" min="0">
                            </div>

                            <!-- Change -->
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-600">Kembalian:</span>
                                <span id="kembalian" class="font-semibold">Rp. 0</span>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                Proses Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Transaksi Berhasil!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Transaksi telah berhasil diproses.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="close-modal"
                        class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">Tutup</button>
                    <a id="print-receipt" href="#" target="_blank"
                        class="block mt-2 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">Cetak
                        Struk</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cart data
            let cart = [];

            // DOM elements
            const cartItemsContainer = document.getElementById('cart-items');
            const subtotalElement = document.getElementById('subtotal');
            const taxElement = document.getElementById('tax');
            const totalElement = document.getElementById('total');
            const dibayarElement = document.getElementById('dibayar');
            const kembalianElement = document.getElementById('kembalian');
            const transactionForm = document.getElementById('transaction-form');
            const successModal = document.getElementById('success-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const printReceiptBtn = document.getElementById('print-receipt');
            const modePesananSelect = document.getElementById('mode_pesanan');
            const tableNumberContainer = document.getElementById('table-number-container');
            const paymentMethodSelect = document.getElementById('metode_pembayaran');
            const nonCashInfo = document.getElementById('non-cash-info');
            const qrisInfo = document.getElementById('qris-info');
            const bankTransferInfo = document.getElementById('bank-transfer-info');

            // Event listeners
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    const menuId = this.dataset.menuId;
                    const menuName = this.dataset.menuName;
                    const menuPrice = parseInt(this.dataset.menuPrice);

                    addToCart(menuId, menuName, menuPrice);
                });
            });

            dibayarElement.addEventListener('input', calculateChange);

            transactionForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (cart.length === 0) {
                    alert('Keranjang masih kosong!');
                    return;
                }

                const total = parseInt(totalElement.textContent.replace(/[^\d]/g, ''));
                const isNonCashPayment = ['QRIS', 'Transfer Bank', 'E-Wallet'].includes(paymentMethodSelect
                    .value);
                const dibayar = isNonCashPayment ? total : (parseInt(dibayarElement.value) || 0);

                if (!isNonCashPayment && dibayar < total) {
                    alert('Pembayaran tidak mencukupi!');
                    return;
                }
                // --- PERBAIKAN DIMULAI DI SINI ---

                // 1. Siapkan data sebagai objek JavaScript
                const items = cart.map(item => ({
                    menu_id: item.id,
                    qty: item.quantity
                }));

                const requestData = {
                    items: items, // Ini adalah array, bukan string
                    mode_pesanan: modePesananSelect.value,
                    metode_pembayaran: document.getElementById('metode_pembayaran').value,
                    dibayar: dibayar
                };

                if (modePesananSelect.value === 'Dine In') {
                    requestData.nomor_meja = document.getElementById('nomor_meja').value;
                }

                // 2. Kirim menggunakan fetch dengan header JSON
                fetch('{{ route('kasir.transaction.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', // Penting: beritahu server bahwa ini JSON
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token tetap dikirim
                        },
                        body: JSON.stringify(requestData) // Stringify SELURUH objek
                    })

                    .then(response => response.json())
                    // Di dalam fetch success handler
                    .then(data => {
                        if (!data.success) {
                            alert(data.message || 'Terjadi kesalahan saat memproses transaksi');
                            return;
                        }
                        const receiptUrl = `/kasir/transaction/${data.transaction_id}/receipt`;

                        if (data.is_non_cash && data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    updateMidtransStatus(data.transaction_id, 'settlement',
                                        result.transaction_status);
                                    showSuccessModal(receiptUrl);
                                },
                                onPending: function(result) {
                                    updateMidtransStatus(data.transaction_id, 'pending',
                                        result.transaction_status);
                                    alert('Pembayaran sedang menunggu penyelesaian.');
                                    showSuccessModal(receiptUrl);
                                },
                                onError: function(result) {
                                    updateMidtransStatus(data.transaction_id, 'deny', result
                                        .transaction_status);
                                    alert('Pembayaran gagal diproses oleh Midtrans.');
                                },
                                onClose: function() {
                                    alert(
                                        'Popup pembayaran ditutup sebelum transaksi selesai.'
                                        );
                                }
                            });

                            return;
                        }

                        showSuccessModal(receiptUrl);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses transaksi');
                    });
            });

            closeModalBtn.addEventListener('click', function() {
                successModal.classList.add('hidden');
            });

            modePesananSelect.addEventListener('change', function() {
                if (this.value === 'Dine In') {
                    tableNumberContainer.classList.remove('hidden');
                } else {
                    tableNumberContainer.classList.add('hidden');
                }
            });

            paymentMethodSelect.addEventListener('change', updatePaymentMethodUI);
            updatePaymentMethodUI();

            // Functions
            function addToCart(id, name, price) {
                // Check if item already in cart
                const existingItemIndex = cart.findIndex(item => item.id == id);

                if (existingItemIndex !== -1) {
                    // Update quantity
                    cart[existingItemIndex].quantity += 1;
                } else {
                    // Add new item
                    cart.push({
                        id: id,
                        name: name,
                        price: price,
                        quantity: 1
                    });
                }

                updateCart();
            }

            function removeFromCart(index) {
                cart.splice(index, 1);
                updateCart();
            }

            function updateQuantity(index, quantity) {
                if (quantity <= 0) {
                    removeFromCart(index);
                } else {
                    cart[index].quantity = quantity;
                    updateCart();
                }
            }

            function updateCart() {
                // Clear cart container
                cartItemsContainer.innerHTML = '';

                if (cart.length === 0) {
                    cartItemsContainer.innerHTML =
                        '<p class="text-gray-500 text-sm">Belum ada item yang dipilih</p>';
                    calculateTotals();
                    return;
                }

                // Add cart items
                cart.forEach((item, index) => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'flex justify-between items-center bg-gray-50 p-2 rounded';

                    itemElement.innerHTML = `
                <div class="flex-1">
                    <p class="font-medium">${item.name}</p>
                    <p class="text-sm text-gray-600">Rp. ${number_format(item.price, 0, ',', '.')} x ${item.quantity}</p>
                </div>
                <div class="flex items-center">
                    <button type="button" class="quantity-minus bg-gray-200 hover:bg-gray-300 rounded-full w-6 h-6 flex items-center justify-center mr-1" data-index="${index}">-</button>
                    <span class="mx-2">${item.quantity}</span>
                    <button type="button" class="quantity-plus bg-gray-200 hover:bg-gray-300 rounded-full w-6 h-6 flex items-center justify-center mr-1" data-index="${index}">+</button>
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 ml-2" data-index="${index}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

                    cartItemsContainer.appendChild(itemElement);
                });

                // Add event listeners to quantity buttons
                document.querySelectorAll('.quantity-minus').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        updateQuantity(index, cart[index].quantity - 1);
                    });
                });

                document.querySelectorAll('.quantity-plus').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        updateQuantity(index, cart[index].quantity + 1);
                    });
                });

                document.querySelectorAll('.remove-item').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        removeFromCart(index);
                    });
                });

                calculateTotals();
            }

            function updatePaymentMethodUI() {
                const paymentMethod = paymentMethodSelect.value;
                const isNonCashPayment = ['QRIS', 'Transfer Bank', 'E-Wallet'].includes(paymentMethod);

                nonCashInfo.classList.toggle('hidden', !isNonCashPayment);
                qrisInfo.classList.toggle('hidden', paymentMethod !== 'QRIS');
                bankTransferInfo.classList.toggle('hidden', paymentMethod !== 'Transfer Bank');

                if (isNonCashPayment) {
                    const total = parseInt(totalElement.textContent.replace(/[^\d]/g, '')) || 0;
                    dibayarElement.value = total;
                    dibayarElement.readOnly = true;
                    dibayarElement.classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    dibayarElement.readOnly = false;
                    dibayarElement.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }

                calculateChange();
            }

            function showSuccessModal(receiptUrl) {
                document.getElementById('success-modal').classList.remove('hidden');
                document.getElementById('print-receipt').href = receiptUrl;
                document.getElementById('transaction-form').reset();
                cart = [];
                updateCart();
            }

            function updateMidtransStatus(transactionId, status, transactionStatus) {
                fetch(`/kasir/transaction/${transactionId}/payment-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: status,
                        transaction_status: transactionStatus || null
                    })
                }).catch((error) => console.error('Failed updating status:', error));
            }

            function calculateTotals() {
                let subtotal = 0;

                cart.forEach(item => {
                    subtotal += item.price * item.quantity;
                });

                const tax = subtotal * 0.1; // 10% tax
                const total = subtotal + tax;

                subtotalElement.textContent = `Rp. ${number_format(subtotal, 0, ',', '.')}`;
                taxElement.textContent = `Rp. ${number_format(tax, 0, ',', '.')}`;
                totalElement.textContent = `Rp. ${number_format(total, 0, ',', '.')}`;

                updatePaymentMethodUI();
            }

            function calculateChange() {
                const total = parseInt(totalElement.textContent.replace(/[^\d]/g, ''));
                const isNonCashPayment = ['QRIS', 'Transfer Bank', 'E-Wallet'].includes(paymentMethodSelect.value);
                const dibayar = isNonCashPayment ? total : (parseInt(dibayarElement.value) || 0);
                const kembalian = dibayar - total;

                kembalianElement.textContent = `Rp. ${number_format(kembalian, 0, ',', '.')}`;
            }

            // Helper function for number formatting
            function number_format(number, decimals, dec_point, thousands_sep) {
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                const n = !isFinite(+number) ? 0 : +number;
                const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
                const sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
                const dec = (typeof dec_point === 'undefined') ? '.' : dec_point;

                let s = '';
                const toFixedFix = function(n, prec) {
                    const k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };

                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                const re = /(-?\d+)(\d{3})/;

                while (re.test(s[0])) {
                    s[0] = s[0].replace(re, '$1' + sep + '$2');
                }

                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }

                return s.join(dec);
            }
        });
    </script>
@endsection
