<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presence_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->nullable()->index('presence_schedules_user_id');
            $table->date('presence_at')->nullable()->index('presence_schedules_at');
            $table->time('hour_from')->nullable();
            $table->time('hour_until')->nullable();
            $table->foreignId('activity_id')->constrained(table: 'employee_activities', indexName: 'presence_schedule_activity_id')->default(1);
            $table->string('notes')->nullable()->default('');
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
        Schema::dropIfExists('presence_schedules');
    }
};
