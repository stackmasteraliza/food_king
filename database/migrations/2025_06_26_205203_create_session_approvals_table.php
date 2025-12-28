<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionApprovalsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('session_approvals')) {
            Schema::create('session_approvals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pos_session_id')->constrained()->onDelete('cascade');
                $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
                $table->decimal('delivered_amount', 8, 2);
                $table->decimal('variance', 8, 2);
                $table->string('status');
                $table->text('comments')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('session_approvals');
    }
}
