<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DebtController;

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
});

require __DIR__.'/auth.php';