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
        Schema::create('reimbursement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reimbursement_id')->constrained(table: 'reimbursements', indexName: 'reimbursement_details_id')->default(1);
            $table->date('transaction_at')->nullable()->index();
            $table->string('description')->nullable()->default('');
            $table->double('nominal')->nullable()->default(0);
            $table->string('attachment')->unique()->nullable();
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
        Schema::dropIfExists('reimbursement_details');
    }
};
