<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\WishlistController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['show']);
    Route::get('/budgets',  [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/reports',             [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-excel',[ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/export-pdf',  [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // Bill Splitting
Route::get('/bills',                    [BillController::class, 'index'])->name('bills.index');
Route::get('/bills/create',             [BillController::class, 'create'])->name('bills.create');
Route::post('/bills',                   [BillController::class, 'store'])->name('bills.store');
Route::get('/bills/{bill}/edit',        [BillController::class, 'edit'])->name('bills.edit');
Route::put('/bills/{bill}',             [BillController::class, 'update'])->name('bills.update');
Route::get('/bills/{bill}/calculate',   [BillController::class, 'calculate'])->name('bills.calculate');
Route::post('/bills/{bill}/save-debt',  [BillController::class, 'saveToDebt'])->name('bills.saveDebt');
Route::delete('/bills/{bill}',          [BillController::class, 'destroy'])->name('bills.destroy');

// Hutang Piutang
Route::get('/debts',                    [DebtController::class, 'index'])->name('debts.index');
Route::get('/debts/create',             [DebtController::class, 'create'])->name('debts.create');
Route::post('/debts',                   [DebtController::class, 'store'])->name('debts.store');
Route::post('/debts/{debt}/mark-paid',  [DebtController::class, 'markPaid'])->name('debts.markPaid');
Route::delete('/debts/{debt}',          [DebtController::class, 'destroy'])->name('debts.destroy');

// Wishlist
Route::get('/wishlists',                        [WishlistController::class, 'index'])->name('wishlists.index');
Route::get('/wishlists/create',                 [WishlistController::class, 'create'])->name('wishlists.create');
Route::post('/wishlists',                       [WishlistController::class, 'store'])->name('wishlists.store');
Route::get('/wishlists/{wishlist}/edit',        [WishlistController::class, 'edit'])->name('wishlists.edit');
Route::put('/wishlists/{wishlist}',             [WishlistController::class, 'update'])->name('wishlists.update');
Route::post('/wishlists/{wishlist}/add-saving', [WishlistController::class, 'addSaving'])->name('wishlists.addSaving');
Route::post('/wishlists/{wishlist}/purchased',  [WishlistController::class, 'markPurchased'])->name('wishlists.purchased');
Route::delete('/wishlists/{wishlist}',          [WishlistController::class, 'destroy'])->name('wishlists.destroy');
});

require __DIR__.'/auth.php';