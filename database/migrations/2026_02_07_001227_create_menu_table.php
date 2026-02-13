<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu', 100);
            $table->integer('harga');
            $table->string('kategori', 50);
            $table->integer('stok')->default(10);
            $table->string('gambar')->default('placeholder.jpg');
            $table->boolean('is_active')->default(true);
            $table->timestamp('updated_at')->nullable(); // Hanya updated_at
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};