<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('pos_received_amount', 19, 6)->after('pos_payment_method')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('pos_received_amount');
        });
    }
};