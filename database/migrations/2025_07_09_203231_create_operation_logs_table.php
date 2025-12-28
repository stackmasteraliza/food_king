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
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('action');  // e.g., delete, update, force_logout
            $table->string('model_type'); // Model being acted on
            $table->unsignedBigInteger('model_id')->nullable(); // ID of affected record
            $table->text('before')->nullable(); // JSON snapshot before
            $table->text('after')->nullable();  // JSON snapshot after
            $table->text('description');
             $table->boolean('seen')->default(false);
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
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
        Schema::dropIfExists('operation_logs');
    }
};
