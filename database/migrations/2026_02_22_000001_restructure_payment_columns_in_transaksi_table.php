<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->renameColumn('metode_pembayaran', 'metode_input');
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('payment_type_midtrans')->nullable()->after('metode_input');
            $table->string('midtrans_order_id')->nullable()->change();
            $table->string('midtrans_transaction_status', 50)->nullable()->after('payment_type_midtrans');
            $table->json('midtrans_response')->nullable()->after('midtrans_transaction_status');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type_midtrans',
                'midtrans_response',
            ]);
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->renameColumn('metode_input', 'metode_pembayaran');
        });
    }
};
