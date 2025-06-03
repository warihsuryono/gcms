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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no', 50)->nullable()->default('');
            $table->date('doc_at')->nullable();
            $table->unsignedBigInteger('use_by')->default(0)->nullable();
            $table->date('use_at')->nullable();
            $table->string('description')->nullable()->default('');
            $table->unsignedBigInteger('currency_id')->default(0)->nullable();
            $table->double('subtotal')->nullable()->default(0);
            $table->double('tax')->nullable()->default(0);
            $table->double('grandtotal')->nullable()->default(0);
            $table->smallInteger('is_approved')->nullable()->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->default(0)->nullable();
            $table->smallInteger('is_acknowledge')->nullable()->default(0);
            $table->dateTime('acknowledge_at')->nullable();
            $table->unsignedBigInteger('acknowledge_by')->default(0)->nullable();
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
        Schema::dropIfExists('purchase_requests');
    }
};
