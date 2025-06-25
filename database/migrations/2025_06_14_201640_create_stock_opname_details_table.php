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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->date('stock_opname_at')->nullable();
            $table->unsignedBigInteger('warehouse_id')->default(0)->nullable()->index();
            $table->text('notes')->nullable();
            $table->smallInteger('is_approved')->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->default(0)->nullable();
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained(table: 'stock_opnames', indexName: 'stock_opname_details_id')->default(1);
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->double('qty')->default(0);
            $table->double('actual_qty')->default(0);
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
        Schema::dropIfExists('stock_opname_details');
        Schema::dropIfExists('stock_opnames');
    }
};
