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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_specification_id')->default(0)->nullable();
            $table->unsignedBigInteger('item_category_id')->default(0)->nullable();
            $table->unsignedBigInteger('item_type_id')->default(0)->nullable();
            $table->unsignedBigInteger('item_brand_id')->default(0)->nullable();
            $table->string('name')->nullable()->default('');
            $table->unsignedBigInteger('unit_id')->default(0)->nullable();
            $table->text('description')->nullable();
            $table->double('minimum_stock')->nullable()->default(0);
            $table->double('maximum_stock')->nullable()->default(0);
            $table->integer('lifetime')->nullable()->default(0);
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
        Schema::dropIfExists('items');
    }
};
