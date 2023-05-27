<?php

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\TableController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderController as FrontendOrderController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TypeIncomeController;
use App\Http\Controllers\Backend\IncomesController;
use App\Http\Controllers\Backend\RecapitulationController;

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

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['prefix' => 'backend', 'middleware' => ['check.role.admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('backend.dasboard');
    Route::get('/dashboard/purchases', [DashboardController::class, 'ChartPengeluaran'])->name('backend.dashboard.chart.purchases');
    Route::get('/menu', [MenuController::class, 'index'])->name('backend.dasboard');

    Route::get('/category', [CategoryController::class, 'index'])->name('backend.category');
    Route::post('/category/create', [CategoryController::class, 'store'])->name('backend.category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('backend.category.edit');
    Route::put('category/update/{id}', [CategoryController::class, 'update'])->name('backend.category.update');
    Route::delete('/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('backend.category.destroy');

    Route::get('/menu', [MenuController::class, 'index'])->name('backend.menu');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('backend.menu.store');
    Route::get('/menu/edit/{id}', [MenuController::class, 'edit'])->name('backend.menu.edit');
    Route::put('/menu/update/{id}', [MenuController::class, 'update'])->name('backend.menu.update');
    Route::delete('/menu/destroy/{id}', [MenuController::class, 'destroy'])->name('backend.menu.destory');
    Route::get('menu/change-status/{id}', [MenuController::class, 'changeStatus'])->name('backend.menu.change-status');

    Route::get('/users', [UserController::class, 'index'])->name('backend.users');
    Route::post('/user', [UserController::class, 'create'])->name('backend.users.create');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('backend.users.show');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('backend.users.update');
    Route::delete('/user/{id}', [UserController::class, 'delete'])->name('backend.users.destroy');

    Route::get('/tables', [TableController::class, 'index'])->name('backend.tables');
    Route::post('/table', [TableController::class, 'create'])->name('backend.tables.create');
    Route::post('/table-increase', [TableController::class, 'increaseTable'])->name('backend.tables.table-increase');
    Route::put('/table-decrease', [TableController::class, 'decreaseTable'])->name('backend.tables.table-decrease');
    Route::get('/table/{id}', [TableController::class, 'show'])->name('backend.tables.show');
    Route::put('/table/{id}', [TableController::class, 'update'])->name('backend.tables.update');
    Route::delete('/table/{id}', [TableController::class, 'delete'])->name('backend.tables.destroy');

    Route::get('/finance/purchases', [PurchaseController::class, 'index'])->name('backend.purchases');
    Route::post('/finance/purchase', [PurchaseController::class, 'create'])->name('backend.purchases.create');
    Route::get('/finance/purchase/{id}', [PurchaseController::class, 'show'])->name('backend.purchases.show');
    Route::put('/finance/purchase/{id}', [PurchaseController::class, 'update'])->name('backend.purchases.update');
    Route::delete('/finance/purchase/{id}', [PurchaseController::class, 'delete'])->name('backend.purchases.destroy');

    Route::get('/finance/typeincome', [TypeIncomeController::class, 'index'])->name('backend.typeincome');
    Route::post('/finance/typeincome/', [TypeIncomeController::class, 'store'])->name('backend.typeincome.create');
    Route::put('/finance/typeincome/{id}', [TypeIncomeController::class, 'update'])->name('backend.typeincome.update');
    Route::delete('/finance/typeincome/{id}', [TypeIncomeController::class, 'destroy'])->name('backend.typeincome.destroy');

    Route::get('/finance/income', [IncomesController::class, 'index'])->name('backend.income');
    Route::post('/finance/income', [IncomesController::class, 'store'])->name('backend.income.store');
    Route::put('finance/income/{id}', [IncomesController::class, 'update'])->name('backend.income.update');
    Route::delete('finance/income/{id}', [IncomesController::class, 'destroy'])->name('backend.income.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('backend.setting');
    Route::put('/setting/update-general-data', [SettingController::class, 'updateGeneralData'])->name('backend.setting.generaldata');
    Route::put('setting/update-modal', [SettingController::class, 'updateModal'])->name('backend.setting.updateModal');
    Route::put('setting/update-icons', [SettingController::class, 'updateLogo'])->name('backend.setting.updateLogo');

    Route::get('/finance/orders', [OrderController::class, 'index'])->name('backend.order.index');
    Route::get('/finance/order/{orderNumber}/invoice', [OrderController::class, 'invoice'])->name('backend.order.invoice');

    Route::get('/finance/recapitulations', [RecapitulationController::class, 'index'])->name('backend.recapitulation.index');
    Route::get('/finance/recapitulations/{type}', [RecapitulationController::class, 'index'])->name('backend.recapitulation.index');
});

Route::get('/backend/finance/recapitulation/{by}/{type}', [RecapitulationController::class, 'recap'])->name('backend.recap');
Route::get('/backend/finance/order/{orderNumber}/invoice', [OrderController::class, 'invoice'])->name('backend.order.invoice');
Route::post('/backend/user/email-validator', [UserController::class, 'emailValidator'])->name('backend.users.email-validator');
Route::put('/backend/finance/order/payment/{id}', [OrderController::class, 'updatePayment'])->name('backend.order.payment');

Route::group(['prefix' => 'frontend', 'middleware' => ['check.role.cashier']], function () {
    Route::get('/order', [FrontendOrderController::class, 'index'])->name('frontend.order.index');
    Route::get('/order-history', [FrontendOrderController::class, 'historyOrder'])->name('frontend.order.history');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('frontend.checkout.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('frontend.profile.index');
    Route::get('user/me', [ProfileController::class, 'me']);
});