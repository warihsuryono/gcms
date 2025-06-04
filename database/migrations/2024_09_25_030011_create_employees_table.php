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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'employee_user_id')->default(1);
            $table->unsignedBigInteger('leader_user_id')->default(0)->nullable();
            $table->unsignedBigInteger('division_id')->default(0)->nullable();
            $table->string('name')->nullable()->default('');
            $table->string('nik', 50)->nullable()->default('');
            $table->string('nip', 20)->nullable()->default('');
            $table->string('contract_no', 30)->nullable()->default('');
            $table->string('bpjs_kesehatan', 30)->nullable()->default('');
            $table->string('bpjs_ketenagakerjaan', 30)->nullable()->default('');
            $table->string('kk', 30)->nullable()->default('');
            $table->string('npwp', 30)->nullable()->default('');
            $table->string('phone', 30)->nullable()->default('');
            $table->date('join_at')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('birth_place', 30)->nullable()->default('');
            $table->date('birth_at')->nullable();
            $table->string('address')->nullable()->default('');
            $table->string('domicile_address')->nullable()->default('');
            $table->foreignId('employee_status_id')->constrained(table: 'employee_statuses', indexName: 'employee_status_id')->default(0);
            $table->foreignId('marriage_status_id')->constrained(table: 'marriage_statuses', indexName: 'marriage_status_id')->default(0);
            $table->foreignId('degree_id')->constrained(table: 'degrees', indexName: 'degree_id')->default(0);
            $table->string('major', 100)->nullable()->default('')->index('major');
            $table->string('level_title', 100)->nullable()->default('');
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
        Schema::dropIfExists('employees');
    }
};
