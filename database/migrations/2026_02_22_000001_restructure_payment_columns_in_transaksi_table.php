<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {});

        Schema::table('transaksi', function (Blueprint $table) {

            $table->string('midtrans_order_id')->nullable()->change();
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
