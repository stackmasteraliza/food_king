<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('pos_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts');
            $table->foreignId('pos_id')->constrained('pos_devices');
            $table->unsignedBigInteger('cashier_id');
            $table->string('session_number')->unique();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->decimal('opening_balance', 10, 2);
            $table->decimal('closing_balance', 10, 2)->nullable();
            $table->string('creator_type',)->nullable();
            $table->bigInteger('creator_id',)->nullable();
            $table->string('editor_type',)->nullable();
            $table->bigInteger('editor_id',)->nullable();
            $table->bigInteger('userConfirm_id',)->nullable();
            $table->timestamp('dateConfirm')->useCurrent();
            $table->integer('status')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_shifts');
    }
};
