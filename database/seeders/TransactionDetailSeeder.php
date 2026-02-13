<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class TransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all transactions and menus
        $transactions = Transaction::all();
        $menus = Menu::all()->keyBy('id');
        
        // Transaction 1 (Kasir 1)
        $this->createTransactionDetails(1, [
            ['menu_id' => 1, 'qty' => 2], // Nasi Goreng Biasa x2
            ['menu_id' => 9, 'qty' => 2], // Es Teh Manis x2
        ]);
        
        // Transaction 2 (Kasir 1)
        $this->createTransactionDetails(2, [
            ['menu_id' => 3, 'qty' => 1], // Nasi Goreng Seafood x1
            ['menu_id' => 6, 'qty' => 1], // Mie Goreng x1
        ]);
        
        // Transaction 3 (Kasir 2)
        $this->createTransactionDetails(3, [
            ['menu_id' => 4, 'qty' => 2], // Nasi Goreng Ayam x2
            ['menu_id' => 7, 'qty' => 1], // Ayam Goreng x1
            ['menu_id' => 10, 'qty' => 2], // Jeruk Hangat x2
        ]);
        
        // Transaction 4 (Kasir 2)
        $this->createTransactionDetails(4, [
            ['menu_id' => 2, 'qty' => 1], // Nasi Goreng Spesial x1
            ['menu_id' => 13, 'qty' => 1], // Kerupuk x1
            ['menu_id' => 9, 'qty' => 1], // Es Teh Manis x1
        ]);
        
        // Transaction 5 (Kasir 1)
        $this->createTransactionDetails(5, [
            ['menu_id' => 5, 'qty' => 2], // Nasi Goreng Kambing x2
            ['menu_id' => 7, 'qty' => 1], // Ayam Goreng x1
            ['menu_id' => 14, 'qty' => 1], // Telur Dadar x1
            ['menu_id' => 10, 'qty' => 3], // Jeruk Hangat x3
        ]);
        
        // Transaction 6 (Kasir 2)
        $this->createTransactionDetails(6, [
            ['menu_id' => 3, 'qty' => 1], // Nasi Goreng Seafood x1
            ['menu_id' => 6, 'qty' => 1], // Mie Goreng x1
            ['menu_id' => 9, 'qty' => 2], // Es Teh Manis x2
        ]);
    }
    
    private function createTransactionDetails($transactionId, $items)
    {
        foreach ($items as $item) {
            $menu = Menu::find($item['menu_id']);
            $harga = $menu->harga;
            $subtotal = $harga * $item['qty'];
            
            TransactionDetail::create([
                'transaksi_id' => $transactionId,
                'menu_id' => $item['menu_id'],
                'harga' => $harga,
                'qty' => $item['qty'],
                'catatan' => '',
                'subtotal' => $subtotal,
            ]);
        }
    }
}