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
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable()->default('');
            $table->string('tagline')->nullable()->default('');
            $table->string('running_text_1')->nullable()->default('');
            $table->string('running_text_2')->nullable()->default('');
            $table->string('running_text_3')->nullable()->default('');
            $table->string('running_text_4')->nullable()->default('');
            $table->string('background')->nullable()->default('');
            $table->string('widget_1')->nullable()->default('');
            $table->integer('widget_1_top')->nullable()->default('');
            $table->integer('widget_1_left')->nullable()->default('');
            $table->string('widget_2')->nullable()->default('');
            $table->integer('widget_2_top')->nullable()->default('');
            $table->integer('widget_2_left')->nullable()->default('');
            $table->string('widget_3')->nullable()->default('');
            $table->integer('widget_3_top')->nullable()->default('');
            $table->integer('widget_3_left')->nullable()->default('');
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
        Schema::dropIfExists('dashboards');
    }
};
