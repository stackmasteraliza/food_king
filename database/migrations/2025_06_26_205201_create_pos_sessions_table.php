<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosSessionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pos_sessions')) {
            Schema::create('pos_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('shift_type_id')->constrained()->onDelete('cascade');
                $table->foreignId('cashier_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('device_id')->constrained()->nullable()->onDelete('cascade');
                $table->timestamp('start_time');
                $table->timestamp('end_time')->nullable();
                $table->decimal('opening_float', 8, 2)->default(0.00);
                $table->decimal('total_sales', 8, 2)->default(0.00);
                $table->decimal('total_refunds', 8, 2)->default(0.00);
                $table->decimal('cash_expected', 8, 2)->default(0.00);
                $table->decimal('cash_actual', 8, 2)->default(0.00);
                $table->string('status')->default('open');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pos_sessions');
    }
}
