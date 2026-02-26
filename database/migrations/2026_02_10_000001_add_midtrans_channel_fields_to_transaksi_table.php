<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('payment_acquirer', 100)->nullable()->after('payment_type_midtrans');
            $table->string('payment_issuer', 100)->nullable()->after('payment_acquirer');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['payment_acquirer', 'payment_issuer']);
        });
    }
};
