<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')
                ->constrained('transaksi')
                ->cascadeOnDelete();

            $table->foreignId('menu_id')->constrained('menu');
            $table->integer('harga');
            $table->integer('qty');
            $table->text('catatan')->nullable();
            $table->integer('subtotal');
            $table->timestamp('updated_at')->nullable(); // Hanya updated_at
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
