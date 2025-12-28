<?php 

use App\Enums\PosPaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{

    public function up(): void 
    {
        Schema::table('orders', function(Blueprint $table){
            $table->tinyInteger('pos_payment_method')->after('payment_method')->nullable();
            $table->string('pos_payment_note', 200)->after('pos_payment_method')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->dropColumn('pos_payment_method');
            $table->dropColumn('pos_payment_note');
        });
    }
};