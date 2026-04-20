<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

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
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');

        $transactions = Transaction::where('user_id', Auth::id())
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date',  $year)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Finku');

        // Header styling
        $sheet->fromArray(
            ['Tanggal', 'Catatan', 'Kategori', 'Tipe', 'Jumlah (Rp)'],
            null, 'A1'
        );

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F1A3A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);

        // Data rows
        $row = 2;
        foreach ($transactions as $tx) {
            $sheet->fromArray([
                $tx->transaction_date->format('d/m/Y'),
                $tx->note ?? '-',
                $tx->category->name,
                $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                (float) $tx->amount,
            ], null, "A{$row}");

            // Warna baris berselang
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F4FC']],
                ]);
            }

            // Warna amount
            $amtColor = $tx->type === 'income' ? '059669' : 'DC2626';
            $sheet->getStyle("E{$row}")->getFont()->getColor()->setRGB($amtColor);

            $row++;
        }

        // Format kolom jumlah sebagai angka
        $sheet->getStyle("E2:E{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        $writer   = new Xlsx($spreadsheet);
        $filename = "laporan-finku-{$monthName}-{$year}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
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
        $monthName    = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        $user         = Auth::user();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'transactions', 'totalIncome', 'totalExpense',
            'balance', 'monthName', 'user'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-finku-{$monthName}.pdf");
    }
}