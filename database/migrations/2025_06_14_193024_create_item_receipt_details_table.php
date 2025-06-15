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
        Schema::create('item_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('item_receipt_no')->unique();
            $table->date('item_receipt_at')->nullable();
            $table->unsignedBigInteger('purchase_order_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('shipment_company')->nullable();
            $table->string('shipment_pic')->nullable();
            $table->string('shipment_phone')->nullable();
            $table->text('shipment_address')->nullable();
            $table->date('shipment_at')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('is_approved')->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->default(0)->nullable();
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('item_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_receipt_id')->constrained(table: 'item_receipts', indexName: 'item_receipt_details_id')->default(1);
            $table->integer('seqno')->nullable()->default(0);
            $table->unsignedBigInteger('purchase_order_detail_id')->default(0)->nullable()->index();
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->double('qty_po')->default(0);
            $table->double('qty_outstanding')->default(0);
            $table->double('qty')->default(0);
            $table->string('sku')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('item_receipt_details');
        Schema::dropIfExists('item_receipts');
    }
};
