<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportTemplateController;
use App\Filament\Resources\CimsConsumptionResource;
use App\Filament\Resources\AttendanceResource\Pages\TapAttendance;
use App\Filament\Resources\CimsCalculationResource;
use App\Filament\Resources\UserSubscriptionResource;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MailConfirmController;
use App\Jobs\SendVerifyMailJob;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\UserVerification;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

// if(config('app.env') == 'production'){
// URL::forceScheme('https');
// }

Route::get('/', function () {
    return redirect(env('PANEL_PATH'));
});
Route::get('login', function () {
    return redirect(env('PANEL_PATH') . '/login');
})->name("login");


Route::get(env('PANEL_PATH') . '/menus/{direction}/{menu}', [MenuController::class, 'reorder'])->name('menus.reorder');
Route::get(env('PANEL_PATH') . '/childmenus/{direction}/{menu}', [MenuController::class, 'reorder'])->name('childmenus.reorder');
Route::get(env('PANEL_PATH') . '/purchase-orders/create/{purchaseRequestDetailIds}', [PurchaseOrderController::class, 'createPurchaseOrder'])->name('purchase-orders.create');

Route::post(env('PANEL_PATH') . '/attendances/tap/save', [TapAttendance::class, 'save'])->name('attendances.tap.save');

Route::get(env('PANEL_PATH') . '/guest', [GuestController::class, 'visit']);
Route::get(env('PANEL_PATH') . '/guest/{redirect}', [GuestController::class, 'visit']);
Route::get(env('PANEL_PATH') . '/continue_as_guest', [GuestController::class, 'continue_as_guest'])->name('continue_as_guest');
