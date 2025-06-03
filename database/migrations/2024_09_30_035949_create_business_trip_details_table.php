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
        Schema::create('business_trip_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_trip_id')->constrained(table: 'business_trips', indexName: 'business_trip_details_id')->default(1);
            $table->string('description', 100)->nullable()->default('');
            $table->double('nominal')->nullable()->default(0);
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
        Schema::dropIfExists('business_trip_details');
    }
};
