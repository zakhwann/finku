<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionsExport
{
    protected $month, $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    public function collection()
    {
        return Transaction::where('user_id', Auth::id())
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date',  $this->year)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->map(fn($tx) => [
                'Tanggal'    => $tx->transaction_date->format('d/m/Y'),
                'Catatan'    => $tx->note ?? '-',
                'Kategori'   => $tx->category->name,
                'Tipe'       => $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                'Jumlah (Rp)' => $tx->amount,
            ]);
    }
}