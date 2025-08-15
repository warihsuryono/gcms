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
        try {
            $menu_id = DB::table('menus')->where('url', 'urgent-work-orders')->first()->id;
            for ($privilege_id = 2; $privilege_id <= 7; $privilege_id++) {
                $menu_ids = DB::table('privileges')->where('id', $privilege_id)->first()->menu_ids . ',' . ($menu_id);
                $privileges = DB::table('privileges')->where('id', $privilege_id)->first()->privileges . ',15';
                DB::table('privileges')->where('id', $privilege_id)->update(['menu_ids' => $menu_ids, 'privileges' => $privileges]);
            }
        } catch (\Exception $e) {
            // Handle exception
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $menu_id = DB::table('menus')->where('url', 'urgent-work-orders')->first()->id;
        for ($privilege_id = 2; $privilege_id <= 7; $privilege_id++) {
            $menu_ids = DB::table('privileges')->where('id', $privilege_id)->first()->menu_ids;
            $menu_ids = str_replace(',' . $menu_id, '', $menu_ids);
            $privileges = DB::table('privileges')->where('id', $privilege_id)->first()->privileges;
            $privileges = substr($privileges, 0, strrpos($privileges, ','));
            DB::table('privileges')->where('id', $privilege_id)->update(['menu_ids' => $menu_ids, 'privileges' => $privileges]);
        }
    }
};
