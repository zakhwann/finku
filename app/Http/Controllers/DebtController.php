<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function index()
    {
        $owes = Debt::where('user_id', Auth::id())
            ->where('type', 'owe')
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get();

        $lends = Debt::where('user_id', Auth::id())
            ->where('type', 'lend')
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get();

        $history = Debt::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $totalOwe  = $owes->sum('remaining');
        $totalLend = $lends->sum('remaining');

        return view('debts.index', compact(
            'owes', 'lends', 'history', 'totalOwe', 'totalLend'
        ));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:owe,lend',
            'person_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount'      => 'required|numeric|min:1',
            'due_date'    => 'nullable|date',
        ]);

        Debt::create([
            'user_id'     => Auth::id(),
            'type'        => $request->type,
            'person_name' => $request->person_name,
            'description' => $request->description,
            'amount'      => $request->amount,
            'paid_amount' => 0,
            'status'      => 'unpaid',
            'due_date'    => $request->due_date,
        ]);

        return redirect()->route('debts.index')
            ->with('success', 'Hutang/piutang berhasil dicatat!');
    }

    public function markPaid(Request $request, Debt $debt)
    {
        abort_if($debt->user_id !== Auth::id(), 403);

        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
        ]);

        $newPaid = $debt->paid_amount + $request->paid_amount;
        $status  = $newPaid >= $debt->amount ? 'paid' : 'partial';

        $debt->update([
            'paid_amount' => min($newPaid, $debt->amount),
            'status'      => $status,
        ]);

        return redirect()->route('debts.index')
            ->with('success', $status === 'paid' ? 'Lunas! 🎉' : 'Pembayaran sebagian berhasil dicatat!');
    }

    public function destroy(Debt $debt)
    {
        abort_if($debt->user_id !== Auth::id(), 403);
        $debt->delete();

        return redirect()->route('debts.index')
            ->with('success', 'Data berhasil dihapus!');
    }
}