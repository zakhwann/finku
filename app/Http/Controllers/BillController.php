<?php

namespace App\Http\Controllers;

use App\Models\BillSession;
use App\Models\BillMember;
use App\Models\BillItem;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        $sessions = BillSession::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();

        return view('bills.index', compact('sessions'));
    }

    public function create()
    {
        return view('bills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'place'    => 'nullable|string|max:100',
            'date'     => 'required|date',
            'members'  => 'required|array|min:2',
            'members.*'=> 'required|string|max:50',
        ]);

        $session = BillSession::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'place'      => $request->place,
            'date'       => $request->date,
            'split_mode' => 'equal',
            'status'     => 'draft',
        ]);

        foreach ($request->members as $name) {
            if (trim($name)) {
                BillMember::create([
                    'bill_session_id' => $session->id,
                    'name'            => trim($name),
                ]);
            }
        }

        return redirect()->route('bills.edit', $session)
            ->with('success', 'Sesi berhasil dibuat! Sekarang input pesanan.');
    }

    public function edit(BillSession $bill)
    {
        abort_if($bill->user_id !== Auth::id(), 403);
        $bill->load('members.items');
        return view('bills.edit', compact('bill'));
    }

    public function update(Request $request, BillSession $bill)
    {
        abort_if($bill->user_id !== Auth::id(), 403);

        $request->validate([
            'tax_percent'      => 'nullable|numeric|min:0|max:100',
            'discount_amount'  => 'nullable|numeric|min:0',
            'split_mode'       => 'required|in:equal,custom',
            'items'            => 'required|array',
            'items.*.name'     => 'required|string',
            'items.*.price'    => 'required|numeric|min:0',
            'items.*.qty'      => 'required|integer|min:1',
            'items.*.member_id'=> 'required|exists:bill_members,id',
        ]);

        // Update session settings
        $bill->update([
            'tax_percent'     => $request->tax_percent ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'split_mode'      => $request->split_mode,
        ]);

        // Hapus items lama & buat ulang
        BillItem::where('bill_session_id', $bill->id)->delete();

        foreach ($request->items as $item) {
            BillItem::create([
                'bill_session_id' => $bill->id,
                'bill_member_id'  => $item['member_id'],
                'name'            => $item['name'],
                'price'           => $item['price'],
                'qty'             => $item['qty'],
            ]);
        }

        return redirect()->route('bills.calculate', $bill);
    }

    public function calculate(BillSession $bill)
    {
        abort_if($bill->user_id !== Auth::id(), 403);
        $bill->load('members.items');

        $subtotal = $bill->items->sum(fn($i) => $i->price * $i->qty);
        $tax      = $subtotal * ($bill->tax_percent / 100);
        $total    = $subtotal + $tax - $bill->discount_amount;

        // Hitung share per member
        $results = $bill->members->map(function ($member) use ($bill, $subtotal, $total) {
            $memberSubtotal = $member->items->sum(fn($i) => $i->price * $i->qty);

            if ($bill->split_mode === 'equal') {
                $share = $total / $bill->members->count();
            } else {
                // Custom: proporsional berdasarkan pesanan
                $share = $subtotal > 0
                    ? ($memberSubtotal / $subtotal) * $total
                    : $total / $bill->members->count();
            }

            // Update share_amount di member
            $member->update([
                'total_items'  => $memberSubtotal,
                'share_amount' => round($share),
            ]);

            return [
                'id'            => $member->id,
                'name'          => $member->name,
                'items_total'   => $memberSubtotal,
                'share_amount'  => round($share),
                'is_payer'      => $member->is_payer,
            ];
        });

        $bill->update(['status' => 'calculated']);

        return view('bills.calculate', compact('bill', 'results', 'subtotal', 'tax', 'total'));
    }

    public function saveToDebt(Request $request, BillSession $bill)
    {
        abort_if($bill->user_id !== Auth::id(), 403);

        $request->validate([
            'payer_id' => 'required|exists:bill_members,id',
        ]);

        // Set payer
        BillMember::where('bill_session_id', $bill->id)->update(['is_payer' => false]);
        BillMember::where('id', $request->payer_id)->update(['is_payer' => true]);

        $payer = BillMember::find($request->payer_id);

        // Buat debt untuk tiap member selain payer
        foreach ($bill->members as $member) {
            if ($member->id !== $payer->id && $member->share_amount > 0) {
                Debt::create([
                    'user_id'         => Auth::id(),
                    'bill_session_id' => $bill->id,
                    'type'            => 'lend',
                    'person_name'     => $member->name,
                    'description'     => "Bill: {$bill->title} ({$bill->place})",
                    'amount'          => $member->share_amount,
                    'paid_amount'     => 0,
                    'status'          => 'unpaid',
                    'due_date'        => now()->addDays(7),
                ]);
            }
        }

        $bill->update(['status' => 'saved']);

        return redirect()->route('debts.index')
            ->with('success', 'Bill berhasil disimpan ke hutang piutang!');
    }

    public function destroy(BillSession $bill)
    {
        abort_if($bill->user_id !== Auth::id(), 403);
        $bill->delete();

        return redirect()->route('bills.index')
            ->with('success', 'Sesi bill berhasil dihapus!');
    }
}