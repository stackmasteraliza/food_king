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
    public function up(): void
    {
        // Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('unit');
            $table->integer('reorder_level')->default(0);
            $table->timestamps();
        });

        // Item-Warehouse Pivot (Stock)
        Schema::create('item_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('avg_cost', 10, 2)->default(0);
            $table->timestamps();
        });

        // Opening Balances
        Schema::create('opening_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('cost', 10, 2);
            $table->date('date');
            $table->timestamps();
        });

        // Purchase Invoices
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('warehouse_id')->constrained();
            $table->date('date');
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        // Purchase Invoice Items
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 12, 2);
            $table->timestamps();
        });

        // Disbursement Orders
        Schema::create('disbursement_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('warehouse_id')->constrained();
            $table->string('reason')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // Disbursement Items
        Schema::create('disbursement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained();
            $table->decimal('quantity', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // Supply Orders
        Schema::create('supply_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('warehouse_id')->constrained();
            $table->string('source')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // Supply Items
        Schema::create('supply_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained();
            $table->decimal('quantity', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_items');
        Schema::dropIfExists('supply_orders');
        Schema::dropIfExists('disbursement_items');
        Schema::dropIfExists('disbursement_orders');
        Schema::dropIfExists('purchase_invoice_items');
        Schema::dropIfExists('purchase_invoices');
        Schema::dropIfExists('opening_balances');
        Schema::dropIfExists('item_warehouse');
        Schema::dropIfExists('items');
        Schema::dropIfExists('warehouses');
    }
};
