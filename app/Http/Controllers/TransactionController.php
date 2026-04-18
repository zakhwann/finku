<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())->with('category');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', date('m', strtotime($request->month)))
                  ->whereYear('transaction_date', date('Y', strtotime($request->month)));
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10);
        $categories   = Category::where('user_id', Auth::id())->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:1',
            'note'             => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create([
            'user_id'          => Auth::id(),
            'category_id'      => $request->category_id,
            'type'             => $request->type,
            'amount'           => $request->amount,
            'note'             => $request->note,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:1',
            'note'             => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($request->only(
            'category_id', 'type', 'amount', 'note', 'transaction_date'
        ));

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diupdate!');
    }

    public function destroy(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}