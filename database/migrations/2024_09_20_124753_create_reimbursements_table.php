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
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'reimbursement_user_id')->default(1);
            $table->foreignId('bank_id')->constrained(table: 'banks', indexName: 'reimbursement_bank_id')->default(1);
            $table->string('bank_account_name')->nullable()->default('');
            $table->string('bank_account_no')->nullable()->default('');
            $table->string('notes')->nullable()->default('');
            $table->double('total')->nullable()->default(0);
            $table->smallInteger('status_payment')->nullable()->default(0);
            $table->smallInteger('is_paid')->nullable()->default(0);
            $table->string('paid_notes')->nullable()->default('');
            $table->smallInteger('is_approved')->nullable()->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->default(0)->nullable();
            $table->string('approved_notes')->nullable()->default('');
            $table->smallInteger('is_acknowledge')->nullable()->default(0);
            $table->dateTime('acknowledge_at')->nullable();
            $table->unsignedBigInteger('acknowledge_by')->default(0)->nullable();
            $table->string('acknowledge_notes')->nullable()->default('');
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
        Schema::dropIfExists('reimbursements');
    }
};
