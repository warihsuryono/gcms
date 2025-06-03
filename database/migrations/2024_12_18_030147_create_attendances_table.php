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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->nullable()->index('attendances_user_id');
            $table->string('photo_in')->nullable()->default('');
            $table->string('photo_out')->nullable()->default('');
            $table->dateTime('tap_in')->nullable();
            $table->dateTime('tap_out')->nullable();
            $table->string('lat_in')->nullable()->default('');
            $table->string('lon_in')->nullable()->default('');
            $table->string('lat_out')->nullable()->default('');
            $table->string('lon_out')->nullable()->default('');
            $table->text('address_in')->nullable();
            $table->text('address_out')->nullable();
            $table->integer('day_minutes')->nullable()->default(0);
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        $seqno = DB::table('menus')->where(['parent_id' => 6])->orderby('seqno', 'desc')->first()->seqno;

        DB::table('menus')->insert([
            ['seqno' => ($seqno + 1), 'parent_id' => 6, 'name' => 'Attendance', 'url' => 'attendances', 'middleware' => 'auth'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
