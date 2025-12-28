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
        Schema::create('user_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users")->onDelete('cascade');
            $table->foreignId('integration_id')->constrained("integrations")->onDelete('cascade');
            $table->string('api_url')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('api_key')->nullable();
            $table->string('client_id')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('auth_method', ['basic', 'token', 'oauth2'])->default('token');
            $table->string('creator_type',)->nullable();
            $table->bigInteger('creator_id',)->nullable();
            $table->string('editor_type',)->nullable();
            $table->bigInteger('editor_id',)->nullable();
            $table->timestamps();
            //creator_type,creator_id,editor_type,editor_id
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_integrations');
    }
};
