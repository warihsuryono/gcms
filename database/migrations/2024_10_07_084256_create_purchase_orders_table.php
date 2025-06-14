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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no', 50)->nullable()->default('')->index();
            $table->date('doc_at')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->default(0)->nullable()->index();
            $table->date('delivery_at')->nullable()->index();
            $table->unsignedBigInteger('payment_type_id')->default(0)->nullable();
            $table->unsignedBigInteger('use_by')->default(0)->nullable()->index();
            $table->date('use_at')->nullable()->index();
            $table->string('shipment_pic')->nullable()->default('');
            $table->text('shipment_address')->nullable();
            $table->unsignedBigInteger('currency_id')->default(0)->nullable();
            $table->smallInteger('discount_is_percentage')->nullable()->default(0);
            $table->double('discount')->nullable()->default(0);
            $table->double('tax')->nullable()->default(0);
            $table->double('subtotal')->nullable()->default(0);
            $table->double('shipping_cost')->nullable()->default(0);
            $table->double('grandtotal')->nullable()->default(0);
            $table->string('notes')->nullable()->default('');
            $table->smallInteger('is_sent')->nullable()->default(0)->index();
            $table->dateTime('sent_at')->nullable();
            $table->unsignedBigInteger('sent_by')->default(0)->nullable();
            $table->smallInteger('is_closed')->nullable()->default(0)->index();
            $table->dateTime('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->default(0)->nullable();
            $table->smallInteger('is_hold')->nullable()->default(0)->index();
            $table->dateTime('hold_at')->nullable();
            $table->unsignedBigInteger('hold_by')->default(0)->nullable();
            $table->string('hold_notes')->nullable()->default('');
            $table->smallInteger('is_approved')->nullable()->default(0)->index();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->default(0)->nullable();
            $table->smallInteger('is_authorized')->nullable()->default(0)->index();
            $table->dateTime('authorized_at')->nullable();
            $table->unsignedBigInteger('authorized_by')->default(0)->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
};
