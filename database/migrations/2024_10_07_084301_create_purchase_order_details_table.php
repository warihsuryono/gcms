<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained(table: 'purchase_orders', indexName: 'purchase_order_detail_id')->default(1);
            $table->integer('seqno')->nullable()->default(0);
            $table->unsignedBigInteger('item_id')->default(0)->nullable();
            $table->double('qty')->nullable()->default(0);
            $table->unsignedBigInteger('unit_id')->default(0)->nullable();
            $table->double('price')->nullable()->default(0);
            $table->string('notes')->nullable()->default('');
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
