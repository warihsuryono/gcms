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
        Schema::create('item_movements', function (Blueprint $table) {
            $table->id();
            $table->dateTime('movement_at')->nullable();
            $table->unsignedBigInteger('item_movement_type_id')->nullable()->index();
            $table->string('doc_no')->nullable()->index();
            $table->unsignedBigInteger('item_request_detail_id')->nullable()->index();
            $table->unsignedBigInteger('item_receipt_detail_id')->nullable()->index();
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->string('sku')->nullable();
            $table->double('qty')->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
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
        Schema::dropIfExists('item_movements');
    }
};
