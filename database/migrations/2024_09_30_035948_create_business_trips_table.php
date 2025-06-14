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
        Schema::create('business_trips', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable()->default('')->index();
            $table->foreignId('province_id')->constrained(table: 'provinces', indexName: 'business_trip_province_id')->default(1);
            $table->foreignId('city_id')->constrained(table: 'cities', indexName: 'business_trip_city_id')->default(1);
            $table->string('destination', 100)->nullable()->default('');
            $table->string('airport_destination', 100)->nullable()->default('');
            $table->unsignedBigInteger('leader_id')->default(0)->nullable();
            $table->unsignedBigInteger('team1_id')->default(0)->nullable();
            $table->unsignedBigInteger('team2_id')->default(0)->nullable();
            $table->unsignedBigInteger('team3_id')->default(0)->nullable();
            $table->unsignedBigInteger('team4_id')->default(0)->nullable();
            $table->date('departure_at')->nullable();
            $table->date('arrival_at')->nullable();
            $table->string('project_name', 100)->nullable()->default('');
            $table->string('hotel', 100)->nullable()->default('');
            $table->foreignId('bank_id')->constrained(table: 'banks', indexName: 'business_trip_bank_id')->default(1);
            $table->string('bank_account_name')->nullable()->default('');
            $table->string('bank_account_no')->nullable()->default('');
            $table->double('total')->nullable()->default(0);
            $table->smallInteger('status_payment')->nullable()->default(0);
            $table->smallInteger('is_approved')->nullable()->default(0)->index();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->default(0)->nullable();
            $table->smallInteger('is_acknowledge')->nullable()->default(0)->index();
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
        Schema::dropIfExists('business_trips');
    }
};
