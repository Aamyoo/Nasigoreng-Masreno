<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::query()->get()->keyBy('nama_menu');
        $users = User::query()->whereIn('username', ['kasir1', 'kasir2'])->get()->keyBy('username');

        $transactions = [
            [
                'username' => 'kasir1',
                'tanggal' => Carbon::now()->subDays(5),
                'nomor_meja' => 'A1',
                'mode_pesanan' => 'Dine In',
                'metode_input' => 'tunai',
                'payment_type_midtrans' => null,
                'payment_status' => 'paid',
                'dibayar' => 50000,
                'items' => [
                    ['menu' => 'Nasi Goreng Biasa', 'qty' => 2],
                    ['menu' => 'Es Teh Manis', 'qty' => 2],
                ],
            ],
            [
                'username' => 'kasir1',
                'tanggal' => Carbon::now()->subDays(4),
                'nomor_meja' => null,
                'mode_pesanan' => 'Take Away',
                'metode_input' => 'midtrans',
                'payment_type_midtrans' => 'qris',
                'payment_status' => 'paid',
                'dibayar' => 44000,
                'items' => [
                    ['menu' => 'Nasi Goreng Seafood', 'qty' => 1],
                    ['menu' => 'Mie Goreng', 'qty' => 1],
                ],
            ],
            [
                'username' => 'kasir2',
                'tanggal' => Carbon::now()->subDays(3),
                'nomor_meja' => 'B2',
                'mode_pesanan' => 'Dine In',
                'metode_input' => 'midtrans',
                'payment_type_midtrans' => 'bank_transfer',
                'payment_status' => 'paid',
                'dibayar' => 78100,
                'items' => [
                    ['menu' => 'Nasi Goreng Ayam', 'qty' => 2],
                    ['menu' => 'Ayam Goreng', 'qty' => 1],
                    ['menu' => 'Es Jeruk', 'qty' => 2],
                ],
            ],
        ];

        foreach ($transactions as $index => $data) {
            $subtotal = 0;
            $details = [];

            foreach ($data['items'] as $item) {
                $menu = $menus->get($item['menu']);
                if (! $menu) {
                    continue;
                }

                $lineSubtotal = $menu->harga * $item['qty'];
                $subtotal += $lineSubtotal;
                $details[] = [
                    'menu_id' => $menu->id,
                    'harga' => $menu->harga,
                    'qty' => $item['qty'],
                    'catatan' => '',
                    'subtotal' => $lineSubtotal,
                ];
            }

            $pajak = (int) round($subtotal * 0.1);
            $total = $subtotal + $pajak;
            $dibayar = (int) $data['dibayar'];

            $transaction = Transaction::create([
                'id_user' => $users[$data['username']]->id,
                'tanggal' => $data['tanggal'],
                'nomor_meja' => $data['nomor_meja'],
                'mode_pesanan' => $data['mode_pesanan'],
                'subtotal' => $subtotal,
                'pajak' => $pajak,
                'total' => $total,
                'metode_input' => $data['metode_input'],
                'payment_type_midtrans' => $data['payment_type_midtrans'],
                'dibayar' => $dibayar,
                'kembalian' => max($dibayar - $total, 0),
                'payment_status' => $data['payment_status'],
                'midtrans_order_id' => $data['metode_input'] === 'midtrans' ? 'SEED-TRX-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT) : null,
            ]);

            foreach ($details as $detail) {
                TransactionDetail::create($detail + ['transaksi_id' => $transaction->id]);
            }
        }
    }
}
