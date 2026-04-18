<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year',  now()->year);

        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->get();

        $budgets = Budget::where('user_id', Auth::id())
            ->where('month', $month)
            ->where('year',  $year)
            ->with('category')
            ->get()
            ->keyBy('category_id');

        // Hitung realisasi pengeluaran per kategori bulan ini
        $realisasi = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(fn($items) => $items->sum('amount'));

        $totalBudget    = $budgets->sum('amount');
        $totalRealisasi = $realisasi->sum();

        return view('budgets.index', compact(
            'categories', 'budgets', 'realisasi',
            'totalBudget', 'totalRealisasi', 'month', 'year'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'budgets'               => 'required|array',
            'budgets.*.category_id' => 'required|exists:categories,id',
            'budgets.*.amount'      => 'required|numeric|min:0',
        ]);

        foreach ($request->budgets as $item) {
            if ($item['amount'] > 0) {
                Budget::updateOrCreate(
                    [
                        'user_id'     => Auth::id(),
                        'category_id' => $item['category_id'],
                        'month'       => $request->month,
                        'year'        => $request->year,
                    ],
                    ['amount' => $item['amount']]
                );
            }
        }

        return redirect()->route('budgets.index', [
            'month' => $request->month,
            'year'  => $request->year,
        ])->with('success', 'Budget berhasil disimpan!');
    }
}