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
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();
            $table->string('item_request_no')->unique();
            $table->date('item_request_at')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('work_order_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->smallInteger('is_issued')->default(0);
            $table->date('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->smallInteger('is_received')->default(0);
            $table->dateTime('receive_at')->nullable();
            $table->unsignedBigInteger('receive_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('item_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_request_id')->constrained(table: 'item_requests', indexName: 'item_request_details_id')->default(1);
            $table->unsignedBigInteger('item_request_type_id')->nullable()->index();
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->double('qty')->default(0);
            $table->double('qty_issued')->default(0);
            $table->string('sku')->nullable();
            $table->string('notes')->nullable();
            $table->string('issued_notes')->nullable();
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
        Schema::dropIfExists('item_request_details');
        Schema::dropIfExists('item_requests');
    }
};
