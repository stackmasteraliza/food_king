<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashMovementsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cash_movements')) {
            Schema::create('cash_movements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pos_session_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 8, 2);
                $table->string('type');
                $table->text('description')->nullable();
                $table->timestamp('timestamp');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cash_movements');
    }
}
