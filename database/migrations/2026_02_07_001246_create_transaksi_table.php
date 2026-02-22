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
            $table->enum('metode_input', ['tunai', 'midtrans']);
            $table->string('payment_type_midtrans', 50)->nullable();
            $table->integer('dibayar');
            $table->integer('kembalian');
            $table->string('payment_status', 20)->default('pending');
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->text('midtrans_snap_token')->nullable();
            $table->string('midtrans_transaction_status', 50)->nullable();
            $table->text('midtrans_qr_url')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
