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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_end')->nullable();
            $table->unsignedBigInteger('division_id')->default(0)->nullable();
            $table->text('field_ids')->nullable();
            $table->text('works')->nullable();
            $table->unsignedBigInteger('work_order_status_id')->default(1)->nullable()->index();
            $table->smallInteger('is_next_work_order')->default(0)->nullable()->comment('0: No, 1: Yes');
            $table->unsignedBigInteger('prev_work_order_id')->default(0)->nullable()->index();
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
        Schema::dropIfExists('work_orders');
    }
};
