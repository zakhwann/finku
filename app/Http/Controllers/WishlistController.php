<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->orderByRaw("FIELD(status, 'ready', 'saving', 'purchased')")
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->get();

        // Hitung rata-rata saving per bulan (3 bulan terakhir)
        $avgMonthlySaving = $this->getAvgMonthlySaving();

        // Update status otomatis
        foreach ($wishlists as $wishlist) {
            if ($wishlist->saved_amount >= $wishlist->target_price
                && $wishlist->status === 'saving') {
                $wishlist->update(['status' => 'ready']);
            }
        }

        return view('wishlists.index', compact('wishlists', 'avgMonthlySaving'));
    }

    public function create()
    {
        return view('wishlists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'target_price' => 'required|numeric|min:1',
            'product_url'  => 'nullable|url',
            'image_url'    => 'nullable|url',
            'priority'     => 'required|in:low,medium,high',
            'target_date'  => 'nullable|date|after:today',
        ]);

        Wishlist::create([
            'user_id'      => Auth::id(),
            'name'         => $request->name,
            'description'  => $request->description,
            'target_price' => $request->target_price,
            'product_url'  => $request->product_url,
            'image_url'    => $request->image_url,
            'priority'     => $request->priority,
            'target_date'  => $request->target_date,
            'status'       => 'saving',
        ]);

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist berhasil ditambahkan!');
    }

    public function edit(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);
        return view('wishlists.edit', compact('wishlist'));
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);

        $request->validate([
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'target_price' => 'required|numeric|min:1',
            'product_url'  => 'nullable|url',
            'image_url'    => 'nullable|url',
            'priority'     => 'required|in:low,medium,high',
            'target_date'  => 'nullable|date',
        ]);

        $wishlist->update($request->only(
            'name', 'description', 'target_price',
            'product_url', 'image_url', 'priority', 'target_date'
        ));

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist berhasil diupdate!');
    }

    public function addSaving(Request $request, Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $newSaved = $wishlist->saved_amount + $request->amount;
        $status   = $newSaved >= $wishlist->target_price ? 'ready' : 'saving';

        $wishlist->update([
            'saved_amount' => min($newSaved, $wishlist->target_price),
            'status'       => $status,
        ]);

        // Catat sebagai transaksi pengeluaran tabungan
        Transaction::create([
            'user_id'          => Auth::id(),
            'category_id'      => $this->getSavingCategoryId(),
            'type'             => 'expense',
            'amount'           => $request->amount,
            'note'             => "Nabung untuk: {$wishlist->name}",
            'transaction_date' => now()->toDateString(),
        ]);

        $msg = $status === 'ready'
            ? "Selamat! Tabungan untuk {$wishlist->name} sudah terkumpul! 🎉"
            : "Berhasil menambah tabungan Rp " . number_format($request->amount, 0, ',', '.') . "!";

        return redirect()->route('wishlists.index')->with('success', $msg);
    }

    public function markPurchased(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);
        $wishlist->update(['status' => 'purchased']);

        return redirect()->route('wishlists.index')
            ->with('success', "{$wishlist->name} ditandai sudah dibeli! 🛍️");
    }

    public function destroy(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);
        $wishlist->delete();

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist berhasil dihapus!');
    }

    private function getAvgMonthlySaving(): float
    {
        $months = collect(range(1, 3))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);
            $income = Transaction::where('user_id', Auth::id())
                ->where('type', 'income')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');
            $expense = Transaction::where('user_id', Auth::id())
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');
            return max($income - $expense, 0);
        });

        return $months->avg() ?? 0;
    }

    private function getSavingCategoryId(): ?int
    {
        $cat = \App\Models\Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereIn('name', ['Tabungan', 'Saving', 'tabungan'])
            ->first();

        if (!$cat) {
            $cat = \App\Models\Category::where('user_id', Auth::id())
                ->where('type', 'expense')
                ->first();
        }

        return $cat?->id;
    }
}