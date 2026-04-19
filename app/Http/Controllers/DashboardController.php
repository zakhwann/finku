<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Budget;
use App\Services\RecommendationService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Total pemasukan & pengeluaran bulan ini
        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // 5 transaksi terbaru
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        // Pengeluaran per kategori bulan ini
        $expenseByCategory = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(fn($items) => $items->sum('amount'));

        // Data chart 6 bulan terakhir
        $chartData = collect(range(5, 0))->map(function ($i) use ($user) {
            $month = Carbon::now()->subMonths($i);
            return [
                'month' => $month->format('M'),
                'income' => Transaction::where('user_id', $user->id)
                    ->where('type', 'income')
                    ->whereMonth('transaction_date', $month->month)
                    ->whereYear('transaction_date', $month->year)
                    ->sum('amount'),
                'expense' => Transaction::where('user_id', $user->id)
                    ->where('type', 'expense')
                    ->whereMonth('transaction_date', $month->month)
                    ->whereYear('transaction_date', $month->year)
                    ->sum('amount'),
            ];
        });

    // Widget budget - ambil budget & realisasi bulan ini
    $budgetWidget = Budget::where('user_id', $user->id)
    ->where('month', $now->month)
    ->where('year', $now->year)
    ->with('category')
    ->get()
    ->map(function ($budget) use ($user, $now) {
        $terpakai = Transaction::where('user_id', $user->id)
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        $pct = $budget->amount > 0
            ? min(round(($terpakai / $budget->amount) * 100), 100)
            : 0;

        return [
            'name'     => $budget->category->name,
            'color'    => $budget->category->color,
            'budget'   => $budget->amount,
            'terpakai' => $terpakai,
            'pct'      => $pct,
            'over'     => $terpakai > $budget->amount,
        ];
    });

    // Warning budget yang sudah melebihi 80%
    $budgetWarnings = Budget::where('user_id', $user->id)
    ->where('month', $now->month)
    ->where('year', $now->year)
    ->with('category')
    ->get()
    ->map(function ($budget) use ($user, $now) {
        $terpakai = Transaction::where('user_id', $user->id)
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        $pct = $budget->amount > 0
            ? round(($terpakai / $budget->amount) * 100)
            : 0;

        return [
            'name'     => $budget->category->name,
            'color'    => $budget->category->color,
            'pct'      => $pct,
            'over'     => $terpakai > $budget->amount,
            'terpakai' => $terpakai,
            'budget'   => $budget->amount,
        ];
    })
    ->filter(fn($b) => $b['pct'] >= 80)
    ->sortByDesc('pct')
    ->values();

    // Widget hutang piutang
    $debtSummary = [
    'totalOwe'  => \App\Models\Debt::where('user_id', $user->id)->where('type', 'owe')->where('status', '!=', 'paid')->sum('amount'),
    'totalLend' => \App\Models\Debt::where('user_id', $user->id)->where('type', 'lend')->where('status', '!=', 'paid')->sum('amount'),
    ];


    // Widget wishlist — tampilkan yang siap dibeli atau progress tertinggi
    $wishlistWidget = \App\Models\Wishlist::where('user_id', $user->id)
    ->whereIn('status', ['saving', 'ready'])
    ->orderByRaw("FIELD(status, 'ready', 'saving')")
    ->limit(3)
    ->get();

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'recentTransactions', 'expenseByCategory', 'chartData', 'budgetWidget', 'budgetWarnings', 'debtSummary', 'wishlistWidget'
        ));
    }
}