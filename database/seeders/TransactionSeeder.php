<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get kasir users
        $kasir1 = User::where('username', 'kasir1')->first();
        $kasir2 = User::where('username', 'kasir2')->first();

        $transactions = [
            [
                'id_user' => $kasir1->id,
                'tanggal' => Carbon::now()->subDays(5)->format('Y-m-d H:i:s'),
                'nomor_meja' => 'A1',
                'mode_pesanan' => 'Dine In',
                'subtotal' => 40000,
                'pajak' => 4000,
                'total' => 44000,
                'metode_pembayaran' => 'Tunai',
                'dibayar' => 50000,
                'kembalian' => 6000,
            ],
            [
                'id_user' => $kasir1->id,
                'tanggal' => Carbon::now()->subDays(4)->format('Y-m-d H:i:s'),
                'nomor_meja' => null,
                'mode_pesanan' => 'Take Away',
                'subtotal' => 35000,
                'pajak' => 3500,
                'total' => 38500,
                'metode_pembayaran' => 'QRIS',
                'dibayar' => 38500,
                'kembalian' => 0,
            ],
            [
                'id_user' => $kasir2->id,
                'tanggal' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s'),
                'nomor_meja' => 'B2',
                'mode_pesanan' => 'Dine In',
                'subtotal' => 66000,
                'pajak' => 6600,
                'total' => 72600,
                'metode_pembayaran' => 'Transfer Bank',
                'dibayar' => 72600,
                'kembalian' => 0,
            ],
            [
                'id_user' => $kasir2->id,
                'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
                'nomor_meja' => null,
                'mode_pesanan' => 'Take Away',
                'subtotal' => 30000,
                'pajak' => 3000,
                'total' => 33000,
                'metode_pembayaran' => 'E-Wallet',
                'dibayar' => 40000,
                'kembalian' => 7000,
            ],
            [
                'id_user' => $kasir1->id,
                'tanggal' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'nomor_meja' => 'A3',
                'mode_pesanan' => 'Dine In',
                'subtotal' => 85000,
                'pajak' => 8500,
                'total' => 93500,
                'metode_pembayaran' => 'Tunai',
                'dibayar' => 100000,
                'kembalian' => 6500,
            ],
            [
                'id_user' => $kasir2->id,
                'tanggal' => Carbon::now()->format('Y-m-d H:i:s'),
                'nomor_meja' => null,
                'mode_pesanan' => 'Take Away',
                'subtotal' => 45000,
                'pajak' => 4500,
                'total' => 49500,
                'metode_pembayaran' => 'QRIS',
                'dibayar' => 49500,
                'kembalian' => 0,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}