<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirTransactionValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_qty_cannot_be_zero_or_negative(): void
    {
        $user = User::create([
            'username' => 'kasirtest',
            'password' => bcrypt('password'),
            'nama_lengkap' => 'Kasir Test',
            'role' => 'kasir',
        ]);

        $menu = Menu::create([
            'nama_menu' => 'Nasi Goreng',
            'harga' => 20000,
            'kategori' => 'Makanan',
            'stok' => 10,
            'gambar' => 'placeholder.jpg',
            'is_active' => true,
        ]);

        $payload = [
            'items' => [
                ['menu_id' => $menu->id, 'qty' => 0],
            ],
            'metode_input' => 'tunai',
            'dibayar' => 50000,
            'mode_pesanan' => 'Dine In',
        ];

        $response = $this->actingAs($user)->postJson(route('kasir.transaction.store'), $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.qty']);
    }

    public function test_total_transaction_is_calculated_in_backend_and_non_negative(): void
    {
        $user = User::create([
            'username' => 'kasirvalid',
            'password' => bcrypt('password'),
            'nama_lengkap' => 'Kasir Valid',
            'role' => 'kasir',
        ]);

        $menu = Menu::create([
            'nama_menu' => 'Es Teh',
            'harga' => 5000,
            'kategori' => 'Minuman',
            'stok' => 10,
            'gambar' => 'placeholder.jpg',
            'is_active' => true,
        ]);

        $payload = [
            'items' => [
                ['menu_id' => $menu->id, 'qty' => 1],
            ],
            'metode_input' => 'tunai',
            'dibayar' => 10000,
            'mode_pesanan' => 'Take Away',
        ];

        $response = $this->actingAs($user)->postJson(route('kasir.transaction.store'), $payload);

        $response->assertOk();

        $transaction = Transaction::findOrFail((int) $response->json('transaction_id'));

        $this->assertSame(5000, (int) $transaction->subtotal);
        $this->assertSame(500, (int) $transaction->pajak);
        $this->assertSame(5500, (int) $transaction->total);
        $this->assertGreaterThanOrEqual(0, (int) $transaction->total);
    }
}
