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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('seqno');
            $table->unsignedBigInteger('parent_id')->default(0)->index('parent_id');
            $table->string('name')->default('');
            $table->string('url')->default('')->nullable();
            $table->string('icon')->nullable();
            $table->string('route')->default('')->nullable();
            $table->string('middleware')->default('')->nullable();
            $table->unsignedBigInteger('deleted_by')->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->default(0)->nullable();
            $table->unsignedBigInteger('updated_by')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('menus')->insert([
            ['id' => 1, 'seqno' => 1, 'parent_id' => 0, 'name' => 'Home', 'url' => '/', 'icon' => 'heroicon-o-home', 'route' => 'App\Http\Controllers\Home::class', 'middleware' => 'auth'],
            ['id' => 2, 'seqno' => 2, 'parent_id' => 0, 'name' => 'Dashboard', 'url' => 'dashboards/show', 'icon' => 'heroicon-o-table-cells', 'route' => '', 'middleware' => 'auth'],
            ['id' => 3, 'seqno' => 3, 'parent_id' => 0, 'name' => 'Master', 'url' => '#', 'icon' => 'heroicon-o-circle-stack', 'route' => '', 'middleware' => ''],
            ['id' => 4, 'seqno' => 4, 'parent_id' => 0, 'name' => 'Activity', 'url' => '#', 'icon' => 'heroicon-o-wrench-screwdriver', 'route' => '', 'middleware' => ''],
            ['id' => 5, 'seqno' => 5, 'parent_id' => 0, 'name' => 'Procurement', 'url' => '#', 'icon' => 'heroicon-o-shopping-cart', 'route' => '', 'middleware' => ''],
            ['id' => 6, 'seqno' => 6, 'parent_id' => 0, 'name' => 'Report', 'url' => '#', 'icon' => 'heroicon-o-wrench-screwdriver', 'route' => '', 'middleware' => ''],
            ['id' => 7, 'seqno' => 7, 'parent_id' => 0, 'name' => 'General', 'url' => '#', 'icon' => 'heroicon-o-wrench-screwdriver', 'route' => '', 'middleware' => ''],

        ]);

        DB::table('menus')->insert([
            ['id' => 8, 'seqno' => 1, 'parent_id' => 3, 'name' => 'Users', 'url' => 'users', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 9, 'seqno' => 2, 'parent_id' => 3, 'name' => 'Followup Officer', 'url' => 'followup-officers', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 10, 'seqno' => 3, 'parent_id' => 3, 'name' => 'Menu', 'url' => 'menus', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 11, 'seqno' => 4, 'parent_id' => 3, 'name' => 'Menu Privileges', 'url' => 'privileges', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 12, 'seqno' => 5, 'parent_id' => 3, 'name' => 'Dashboard', 'url' => 'dashboards', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 13, 'seqno' => 6, 'parent_id' => 3, 'name' => 'Divisions', 'url' => 'divisions', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 14, 'seqno' => 7, 'parent_id' => 3, 'name' => 'Units', 'url' => 'units', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 15, 'seqno' => 8, 'parent_id' => 3, 'name' => 'Payment Types', 'url' => 'payment-types', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 16, 'seqno' => 9, 'parent_id' => 3, 'name' => 'Work Order Statuses', 'url' => 'work-order-statuses', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 17, 'seqno' => 10, 'parent_id' => 3, 'name' => 'Provinces', 'url' => 'provinces', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 18, 'seqno' => 11, 'parent_id' => 3, 'name' => 'Cities', 'url' => 'cities', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 19, 'seqno' => 12, 'parent_id' => 3, 'name' => 'Fields', 'url' => 'fields', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 20, 'seqno' => 13, 'parent_id' => 3, 'name' => 'Warehouses', 'url' => 'warehouses', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 21, 'seqno' => 14, 'parent_id' => 3, 'name' => 'Suppliers', 'url' => 'suppliers', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 22, 'seqno' => 15, 'parent_id' => 3, 'name' => 'Item Specifications', 'url' => 'item-specifications', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 23, 'seqno' => 16, 'parent_id' => 3, 'name' => 'Item Categories', 'url' => 'item-categories', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 24, 'seqno' => 17, 'parent_id' => 3, 'name' => 'Item Types', 'url' => 'item-types', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 25, 'seqno' => 18, 'parent_id' => 3, 'name' => 'Item Brands', 'url' => 'item-brands', 'icon' => '', 'route' => '', 'middleware' => 'auth'],
            ['id' => 26, 'seqno' => 19, 'parent_id' => 3, 'name' => 'Items', 'url' => 'items', 'icon' => '', 'route' => '', 'middleware' => 'auth'],

        ]);

        DB::table('menus')->insert([
            ['id' => 27, 'seqno' => 1, 'parent_id' => 4, 'name' => 'Fuel Consumptions', 'url' => 'fuel-consumptions', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 28, 'seqno' => 2, 'parent_id' => 4, 'name' => 'Work Orders', 'url' => 'work-orders', 'icon' => '', 'route' => '', 'middleware' => ''],
        ]);

        DB::table('menus')->insert([
            ['id' => 29, 'seqno' => 1, 'parent_id' => 5, 'name' => 'Purchase Requests', 'url' => 'purchase-requests', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 30, 'seqno' => 2, 'parent_id' => 5, 'name' => 'Purchase Orders', 'url' => 'purchase-orders', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 31, 'seqno' => 3, 'parent_id' => 5, 'name' => 'Item Requests', 'url' => 'item-requests', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 32, 'seqno' => 3, 'parent_id' => 5, 'name' => 'Item Receipts', 'url' => 'item-receipts', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 33, 'seqno' => 4, 'parent_id' => 5, 'name' => 'Item Controls', 'url' => 'item-controls', 'icon' => '', 'route' => '', 'middleware' => ''],
        ]);

        DB::table('menus')->insert([
            ['id' => 34, 'seqno' => 1, 'parent_id' => 6, 'name' => 'Fuel Consumptions', 'url' => 'fuel-consumption-reports', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 35, 'seqno' => 2, 'parent_id' => 6, 'name' => 'Work Orders', 'url' => 'work-order-reports', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 36, 'seqno' => 3, 'parent_id' => 6, 'name' => 'Purchase Requests', 'url' => 'purchase-request-reports', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 37, 'seqno' => 4, 'parent_id' => 6, 'name' => 'Purchase Orders', 'url' => 'purchase-order-reports', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 38, 'seqno' => 5, 'parent_id' => 6, 'name' => 'Item Stocks', 'url' => 'item-stock-reports', 'icon' => '', 'route' => '', 'middleware' => ''],
            ['id' => 39, 'seqno' => 6, 'parent_id' => 6, 'name' => 'Item Movements', 'url' => 'item-movement-reports', 'icon' => '', 'route' => '', 'middleware' => ''],
        ]);

        DB::table('menus')->insert([
            ['id' => 40, 'seqno' => 1, 'parent_id' => 7, 'name' => 'Profile', 'url' => 'profiles', 'icon' => '', 'route' => '', 'middleware' => ''],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
