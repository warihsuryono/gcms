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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->enum('import_domestic', ['import', 'domestic', 'both']);
            $table->string('name', 100)->nullable()->default('');
            $table->string('pic', 50)->nullable()->default('');
            $table->string('pic_phone', 30)->nullable()->default('');
            $table->string('email', 100)->nullable()->default('');
            $table->string('address')->nullable()->default('');
            $table->unsignedBigInteger('city_id')->default(0)->nullable();
            $table->unsignedBigInteger('province_id')->default(0)->nullable();
            $table->string('country', 100)->nullable()->default('');
            $table->string('zipcode', 10)->nullable()->default('');
            $table->string('fax', 30)->nullable()->default('');
            $table->string('nationality', 100)->nullable()->default('');
            $table->string('remarks')->nullable()->default('');
            $table->string('npwp', 50)->nullable()->default('');
            $table->string('nppkp', 50)->nullable()->default('');
            $table->string('tax_invoice_no', 50)->nullable()->default('');
            $table->unsignedBigInteger('payment_type_id')->default(0)->nullable();
            $table->unsignedBigInteger('bank_id')->default(0)->nullable();
            $table->string('bank_account_name')->nullable()->default('');
            $table->string('bank_account_no')->nullable()->default('');
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
        Schema::dropIfExists('suppliers');
    }
};
