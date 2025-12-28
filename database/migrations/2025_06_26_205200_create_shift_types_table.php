<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftTypesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('shift_types')) {
            Schema::create('shift_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('parent_shift_id')->nullable()->constrained('shift_types')->onDelete('set null');
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('shift_types');
    }
}
