<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->dateTime('tanggal');
            $table->string('nomor_meja', 10)->nullable();
            $table->enum('mode_pesanan', ['Dine In', 'Take Away'])->default('Take Away');
            $table->integer('subtotal');
            $table->integer('pajak');
            $table->integer('total');
            $table->enum('metode_pembayaran', ['Tunai', 'QRIS', 'Transfer Bank', 'E-Wallet']);
            $table->integer('dibayar');
            $table->integer('kembalian');
            $table->timestamp('updated_at')->nullable(); // Hanya updated_at
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
