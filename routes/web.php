<?php

use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\RootController;
use App\Http\Controllers\Installer\InstallerController;
use App\Http\PaymentGateways\Gateways\Paytm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\POSSessionController;
use App\Http\Controllers\Admin\ShiftTypeController;
use App\Http\Controllers\Admin\SessionApprovalController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::prefix('install')->name('installer.')->middleware(['web'])->group(function () {
    Route::get('/', [InstallerController::class, 'index'])->name('index');
    Route::get('/requirement', [InstallerController::class, 'requirement'])->name('requirement');
    Route::get('/permission', [InstallerController::class, 'permission'])->name('permission');
    Route::get('/license', [InstallerController::class, 'license'])->name('license');
    Route::post('/license', [InstallerController::class, 'licenseStore'])->name('licenseStore');
    Route::get('/site', [InstallerController::class, 'site'])->name('site');
    Route::post('/site', [InstallerController::class, 'siteStore'])->name('siteStore');
    Route::get('/database', [InstallerController::class, 'database'])->name('database');
    Route::post('/database', [InstallerController::class, 'databaseStore'])->name('databaseStore');
    Route::get('/final', [InstallerController::class, 'final'])->name('final');
    Route::get('/final-store', [InstallerController::class, 'finalStore'])->name('finalStore');
});


// POS Routes  //Anwar 
Route::prefix('pos')->name('admin.pos.')->middleware(['installed', 'auth'])->group(function () {
    Route::get('/', [POSSessionController::class, 'dashboard'])->name('dashboard');
    Route::get('/active-session', [POSSessionController::class, 'checkActiveSession'])->name('active-session');
    Route::get('/dashboard-stats', [POSSessionController::class, 'getDashboardStats'])->name('dashboard-stats');
    Route::get('/sessions', [POSSessionController::class, 'index'])->name('sessions.index');
    Route::get('/start', [POSSessionController::class, 'create'])->name('start');
    Route::post('/start', [POSSessionController::class, 'start'])->name('start.store');
    Route::get('/active', [POSSessionController::class, 'active'])->name('active');
    Route::post('/end', [POSSessionController::class, 'end'])->name('end');
    Route::get('/summary/{id}', [POSSessionController::class, 'summary'])->name('summary');
    Route::match(['get', 'post'], '/cash-movement', [POSSessionController::class, 'cashMovement'])->name('cash.movement');
    // Reports & export
    Route::get('/sessions/export', [POSSessionController::class, 'exportCsv'])->name('sessions.export');

    Route::resource('shift-types', ShiftTypeController::class);

    Route::get('/approvals', [SessionApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approve/{sessionId}', [SessionApprovalController::class, 'create'])->name('approvals.create');
    Route::post('/approve/{sessionId}', [SessionApprovalController::class, 'store'])->name('approvals.store');
});
Route::prefix('pos')->middleware(['installed', 'localization'])
    ->name('pos.')->group(function () {
        // Session summary
        Route::get('summary/{id}', [\App\Http\Controllers\Admin\PosController::class, 'getSessionSummary'])
            ->name('session.summary');
        // Shift Types 
        Route::get('shift-types', [ShiftTypeController::class, 'apiIndex']);

        // POS Sessions 
        Route::get('active-session', [POSSessionController::class, 'checkActiveSession']);
        Route::get('sessions/active', [POSSessionController::class, 'checkActiveSession']);
        Route::post('sessions', [POSSessionController::class, 'apiStore']);
    });

Route::get('/', [RootController::class, 'index'])->middleware(['installed'])->name('home');
Route::prefix('payment')->name('payment.')->middleware(['installed'])->group(function () {
    Route::get('/{order}/pay', [PaymentController::class, 'index'])->name('index');
    Route::post('/{order}/pay', [PaymentController::class, 'payment'])->name('store');
    Route::match(['get', 'post'], '/{paymentGateway:slug}/{order}/success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], '/{paymentGateway:slug}/{order}/fail', [PaymentController::class, 'fail'])->name('fail');
    Route::match(['get', 'post'], '/{paymentGateway:slug}/{order}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/successful/{order}', [PaymentController::class, 'successful'])->name('successful');
});

Route::get('/{any}', [RootController::class, 'index'])->middleware(['installed'])->where(['any' => '.*']);
