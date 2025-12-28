<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashVariantsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cash_variants')) {
            Schema::create('cash_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pos_session_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 8, 2);
                $table->text('reason');
                $table->timestamp('timestamp');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cash_variants');
    }
}
