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
        Schema::create('attendance_targets', function (Blueprint $table) {
            $table->id();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->integer('target')->nullable()->default(0);
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        if (!DB::table("menus")->where("url", "attendance-targets")->exists()) {
            DB::table("menus")->insert([
                "parent_id" => 2,
                "name" => "Attendance Targets",
                "url" => "attendance-targets",
                "seqno" => DB::table("menus")->where("parent_id", 2)->max("seqno") + 1
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_targets');
    }
};
