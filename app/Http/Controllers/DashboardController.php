<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'recentTransactions', 'expenseByCategory', 'chartData'
        ));
    }
}