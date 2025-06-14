<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PurchaseOrderController;

// if(config('app.env') == 'production'){
// URL::forceScheme('https');
// }

Route::get('/', function () {
    return redirect(env('PANEL_PATH'));
})->name('home');

Route::get('login', function () {
    return redirect(env('PANEL_PATH') . '/login');
})->name("login");


Route::get(env('PANEL_PATH') . '/menus/{direction}/{menu}', [MenuController::class, 'reorder'])->name('menus.reorder');
Route::get(env('PANEL_PATH') . '/childmenus/{direction}/{menu}', [MenuController::class, 'reorder'])->name('childmenus.reorder');
Route::get(env('PANEL_PATH') . '/purchase-orders/create/{purchaseRequestDetailIds}', [PurchaseOrderController::class, 'createPurchaseOrder'])->name('purchase-orders.create');
