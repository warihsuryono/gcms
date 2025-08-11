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
        $id = DB::table('menus')->orderby('id', 'desc')->first()->id;
        $seqno = DB::table('menus')->where(['parent_id' => 0])->orderby('seqno', 'desc')->first()->seqno;
        DB::table('menus')->insert([
            ['id' => ($id + 1), 'seqno' => ($seqno + 1), 'parent_id' => 0, 'name' => 'Urgent Work Orders', 'url' => 'urgent-work-orders', 'icon' => 'heroicon-o-cog', 'route' => '', 'middleware' => ''],
        ]);
        DB::table('menus')->where('id', 6)->update(['icon' => 'heroicon-o-presentation-chart-bar']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('menus')->where(['url' => 'urgent-work-orders'])->delete();
    }
};
