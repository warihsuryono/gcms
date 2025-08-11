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
        Schema::create('urgent_work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->default('');
            $table->dateTime('work_at')->nullable();
            $table->unsignedBigInteger('division_id')->default(0)->nullable();
            $table->unsignedBigInteger('field_id')->default(0)->nullable();
            $table->string('lat')->nullable()->default('');
            $table->string('lon')->nullable()->default('');
            $table->text('works')->nullable();
            $table->unsignedBigInteger('work_order_status_id')->default(1)->nullable()->index();
            $table->string('photo_1')->nullable()->default('');
            $table->string('photo_2')->nullable()->default('');
            $table->string('photo_3')->nullable()->default('');
            $table->string('photo_4')->nullable()->default('');
            $table->string('photo_5')->nullable()->default('');
            $table->string('pic')->nullable()->default('');
            $table->unsignedBigInteger('accepted_by')->default(0)->nullable();
            $table->dateTime('accepted_at')->nullable();
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
        Schema::dropIfExists('urgent_work_orders');
    }
};
