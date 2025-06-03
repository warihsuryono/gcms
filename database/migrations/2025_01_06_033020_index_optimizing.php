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
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('tap_in');
            $table->index('tap_out');
        });
        Schema::table('business_trips', function (Blueprint $table) {
            $table->index('doc_no');
            $table->index('is_approved');
            $table->index('is_acknowledge');
        });
        Schema::table('business_trip_payments', function (Blueprint $table) {
            $table->index('paid_at');
        });
        Schema::table('currencies', function (Blueprint $table) {
            $table->index('code');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->index('item_specification_id');
            $table->index('item_category_id');
            $table->index('item_type_id');
            $table->index('item_brand_id');
        });
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->index('doc_no');
            $table->index('doc_at');
            $table->index('supplier_id');
            $table->index('delivery_at');
            $table->index('purchase_request_id');
            $table->index('use_by');
            $table->index('use_at');
            $table->index('is_sent');
            $table->index('is_closed');
            $table->index('is_hold');
            $table->index('is_approved');
            $table->index('is_authorized');
        });
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->index('item_id');
        });
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->index('doc_no');
            $table->index('doc_at');
            $table->index('use_by');
            $table->index('use_at');
            $table->index('is_approved');
            $table->index('is_acknowledge');
        });
        Schema::table('purchase_request_details', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->index('item_id');
            $table->index('is_purchase_order');
            $table->index('purchase_order_id');
        });
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->index('is_paid');
            $table->index('is_approved');
            $table->index('is_acknowledge');
        });
        Schema::table('reimbursement_details', function (Blueprint $table) {
            $table->index('transaction_at');
        });
        Schema::table('reimbursement_payments', function (Blueprint $table) {
            $table->index('paid_at');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('import_domestic');
            $table->index('city_id');
            $table->index('province_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('tap_in');
            $table->dropIndex('tap_out');
        });
        Schema::table('business_trips', function (Blueprint $table) {
            $table->dropIndex('doc_no');
            $table->dropIndex('is_approved');
            $table->dropIndex('is_acknowledge');
        });
        Schema::table('business_trip_payments', function (Blueprint $table) {
            $table->dropIndex('paid_at');
        });
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropIndex('code');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('item_specification_id');
            $table->dropIndex('item_category_id');
            $table->dropIndex('item_type_id');
            $table->dropIndex('item_brand_id');
        });
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndex('doc_no');
            $table->dropIndex('doc_at');
            $table->dropIndex('supplier_id');
            $table->dropIndex('delivery_at');
            $table->dropIndex('purchase_request_id');
            $table->dropIndex('use_by');
            $table->dropIndex('use_at');
            $table->dropIndex('is_sent');
            $table->dropIndex('is_closed');
            $table->dropIndex('is_hold');
            $table->dropIndex('is_approved');
            $table->dropIndex('is_authorized');
        });
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->dropIndex('item_id');
        });
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropIndex('doc_no');
            $table->dropIndex('doc_at');
            $table->dropIndex('use_by');
            $table->dropIndex('use_at');
            $table->dropIndex('is_approved');
            $table->dropIndex('is_acknowledge');
        });
        Schema::table('purchase_request_details', function (Blueprint $table) {
            $table->dropIndex('supplier_id');
            $table->dropIndex('item_id');
            $table->dropIndex('is_purchase_order');
            $table->dropIndex('purchase_order_id');
        });
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->dropIndex('is_paid');
            $table->dropIndex('is_approved');
            $table->dropIndex('is_acknowledge');
        });
        Schema::table('reimbursement_details', function (Blueprint $table) {
            $table->dropIndex('transaction_at');
        });
        Schema::table('reimbursement_payments', function (Blueprint $table) {
            $table->dropIndex('paid_at');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex('import_domestic');
            $table->dropIndex('city_id');
            $table->dropIndex('province_id');
        });
    }
};
