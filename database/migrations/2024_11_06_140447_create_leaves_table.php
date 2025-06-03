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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->foreign('user_id')->references('id')->on('users')->index();
            $table->integer("leave_type_id")->nullable()->index();
            $table->text("reason")->nullable();
            $table->string("document")->nullable();
            $table->integer("day_off")->nullable()->comment("Jumlah hari libur pada rentang izin")->default(0);
            $table->integer("day_leave")->nullable()->comment("Jumlah hari aktual izin");
            $table->integer("is_approved")->default(0)->nullable();
            $table->integer("approved_by")->nullable();
            $table->integer("is_acknowledge")->default(0)->nullable();
            $table->integer("acknowledge_by")->nullable();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->timestamp("start_at")->nullable();
            $table->timestamp("end_at")->nullable();
            $table->timestamp("approved_at")->nullable();
            $table->timestamp("acknowledge_at")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
