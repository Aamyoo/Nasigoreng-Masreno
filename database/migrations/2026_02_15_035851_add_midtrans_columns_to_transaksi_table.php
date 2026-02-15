<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('payment_status', 20)->default('settlement')->after('kembalian');
            $table->string('midtrans_order_id')->nullable()->unique()->after('payment_status');
            $table->text('midtrans_snap_token')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_transaction_status', 50)->nullable()->after('midtrans_snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'midtrans_order_id',
                'midtrans_snap_token',
                'midtrans_transaction_status',
            ]);
        });
    }
};
