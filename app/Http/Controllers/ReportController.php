<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year',  now()->year);

        $transactions = Transaction::where('user_id', Auth::id())
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date',  $year)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category.name')
            ->map(fn($items) => $items->sum('amount'));

        return view('reports.index', compact(
            'transactions', 'totalIncome', 'totalExpense',
            'balance', 'expenseByCategory', 'month', 'year'
        ));
    }

    public function exportExcel(Request $request)
{
    $month     = $request->get('month', now()->month);
    $year      = $request->get('year',  now()->year);
    $monthName = Carbon::createFromDate($year, $month, 1)->format('F');;

    $export = new TransactionsExport($month, $year);

    return (new FastExcel($export->collection()))
        ->download("laporan-finku-{$monthName}-{$year}.xlsx");
}

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year',  now()->year);

        $transactions = Transaction::where('user_id', Auth::id())
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date',  $year)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;
        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        $user         = Auth::user();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'transactions', 'totalIncome', 'totalExpense',
            'balance', 'monthName', 'user'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-finku-{$monthName}.pdf");
    }
}