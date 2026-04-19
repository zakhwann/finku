<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecommendationService
{
    public function getRecommendations(): array
    {
        $user  = Auth::user();
        $now   = Carbon::now();
        $recs  = [];

        // Ambil semua budget bulan ini
        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->with('category')
            ->get();

        foreach ($budgets as $budget) {
            $terpakai = Transaction::where('user_id', $user->id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $now->month)
                ->whereYear('transaction_date', $now->year)
                ->sum('amount');

            $sisa = $budget->amount - $terpakai;
            $pct  = $budget->amount > 0
                ? round(($terpakai / $budget->amount) * 100)
                : 0;

            $catName = strtolower($budget->category->name);

            // Kategori hampir habis (>= 80%)
            if ($pct >= 80 && $pct < 100) {
                $recs[] = [
                    'type'    => 'warning',
                    'icon'    => '⚠',
                    'title'   => "Budget {$budget->category->name} hampir habis",
                    'message' => "Tersisa Rp " . number_format($sisa, 0, ',', '.') . " ({$pct}% terpakai). Pertimbangkan untuk mengurangi pengeluaran kategori ini.",
                    'action'  => null,
                    'color'   => '#f59e0b',
                    'bg'      => '#fffbeb',
                    'border'  => '#fde68a',
                ];
            }

            // Kategori sudah over budget
            if ($pct >= 100) {
                $recs[] = [
                    'type'    => 'danger',
                    'icon'    => '!',
                    'title'   => "Over budget — {$budget->category->name}",
                    'message' => "Kamu sudah melebihi budget kategori ini. Hindari pengeluaran " . $budget->category->name . " sampai akhir bulan.",
                    'action'  => null,
                    'color'   => '#dc2626',
                    'bg'      => '#fef2f2',
                    'border'  => '#fecaca',
                ];
            }

            // Budget masih aman (< 50%) — berikan rekomendasi positif
            if ($pct < 50 && $sisa > 0) {
                $suggestion = $this->getSuggestion($catName, $sisa);
                if ($suggestion) {
                    $recs[] = [
                        'type'    => 'info',
                        'icon'    => '◉',
                        'title'   => $suggestion['title'],
                        'message' => $suggestion['message'],
                        'action'  => $suggestion['action'] ?? null,
                        'color'   => '#2e5fba',
                        'bg'      => '#eff4ff',
                        'border'  => '#bfdbfe',
                    ];
                }
            }
        }

        // Cek kalau tidak ada budget sama sekali
        if ($budgets->isEmpty()) {
            $recs[] = [
                'type'    => 'info',
                'icon'    => '◎',
                'title'   => 'Belum ada budget bulan ini',
                'message' => 'Set budget per kategori untuk mendapatkan rekomendasi pengeluaran yang lebih cerdas.',
                'action'  => ['label' => 'Set Budget →', 'url' => route('budgets.index')],
                'color'   => '#2e5fba',
                'bg'      => '#eff4ff',
                'border'  => '#bfdbfe',
            ];
        }

        // Cek saving rate
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

        if ($totalIncome > 0) {
            $savingRate = round((($totalIncome - $totalExpense) / $totalIncome) * 100);

            if ($savingRate >= 30) {
                $recs[] = [
                    'type'    => 'success',
                    'icon'    => '✓',
                    'title'   => "Saving rate kamu {$savingRate}% — Luar biasa!",
                    'message' => 'Kamu berhasil menyimpan lebih dari 30% pemasukan bulan ini. Pertahankan kebiasaan baik ini!',
                    'action'  => null,
                    'color'   => '#059669',
                    'bg'      => '#ecfdf5',
                    'border'  => '#a7f3d0',
                ];
            } elseif ($savingRate < 10 && $savingRate >= 0) {
                $recs[] = [
                    'type'    => 'warning',
                    'icon'    => '⚠',
                    'title'   => "Saving rate kamu hanya {$savingRate}%",
                    'message' => 'Coba kurangi pengeluaran tidak penting dan targetkan saving rate minimal 20% dari pemasukan.',
                    'action'  => ['label' => 'Lihat Laporan →', 'url' => route('reports.index')],
                    'color'   => '#f59e0b',
                    'bg'      => '#fffbeb',
                    'border'  => '#fde68a',
                ];
            } elseif ($savingRate < 0) {
                $recs[] = [
                    'type'    => 'danger',
                    'icon'    => '!',
                    'title'   => 'Pengeluaran melebihi pemasukan!',
                    'message' => 'Kamu menghabiskan lebih dari yang kamu dapatkan bulan ini. Segera evaluasi pengeluaranmu.',
                    'action'  => ['label' => 'Lihat Transaksi →', 'url' => route('transactions.index')],
                    'color'   => '#dc2626',
                    'bg'      => '#fef2f2',
                    'border'  => '#fecaca',
                ];
            }
        }

        // Cek hutang jatuh tempo
        $debtsDue = \App\Models\Debt::where('user_id', $user->id)
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->get();

        foreach ($debtsDue as $debt) {
            $recs[] = [
                'type'    => 'danger',
                'icon'    => '!',
                'title'   => "Hutang ke {$debt->person_name} jatuh tempo!",
                'message' => "Rp " . number_format($debt->remaining, 0, ',', '.') . " jatuh tempo " . $debt->due_date->diffForHumans() . ". Segera selesaikan.",
                'action'  => ['label' => 'Lihat Hutang →', 'url' => route('debts.index')],
                'color'   => '#dc2626',
                'bg'      => '#fef2f2',
                'border'  => '#fecaca',
            ];
        }

        return $recs;
    }

    private function getSuggestion(string $catName, float $sisa): ?array
    {
        $sisaFormatted = 'Rp ' . number_format($sisa, 0, ',', '.');

        if (str_contains($catName, 'makan') || str_contains($catName, 'food')) {
            return [
                'title'   => "Budget makan masih aman — {$sisaFormatted} tersisa",
                'message' => 'Budget makanmu masih sehat. Kalau mau coba tempat makan baru, ini saat yang tepat tanpa khawatir over budget.',
                'action'  => null,
            ];
        }

        if (str_contains($catName, 'transport') || str_contains($catName, 'bensin')) {
            return [
                'title'   => "Budget transportasi masih {$sisaFormatted}",
                'message' => 'Pertimbangkan pakai transportasi umum lebih sering untuk menghemat sisa budget transportasimu.',
                'action'  => null,
            ];
        }

        if (str_contains($catName, 'pendidikan') || str_contains($catName, 'buku') || str_contains($catName, 'kursus')) {
            return [
                'title'   => "Ada {$sisaFormatted} untuk investasi pendidikan",
                'message' => 'Budget pendidikanmu masih cukup. Pertimbangkan beli buku, ikut kursus online, atau upgrade skill yang relevan dengan jurusanmu.',
                'action'  => ['label' => 'Lihat Budget →', 'url' => route('budgets.index')],
            ];
        }

        if (str_contains($catName, 'hiburan') || str_contains($catName, 'game') || str_contains($catName, 'entertainment')) {
            return [
                'title'   => "Budget hiburan masih {$sisaFormatted}",
                'message' => 'Masih ada ruang untuk hiburan bulan ini. Tapi ingat, tetap prioritaskan kebutuhan penting dulu ya!',
                'action'  => null,
            ];
        }

        if (str_contains($catName, 'tabungan') || str_contains($catName, 'saving')) {
            return [
                'title'   => "Tetap konsisten menabung!",
                'message' => "Kamu masih punya {$sisaFormatted} di budget tabungan. Jangan lupa transfer ke rekening tabungan sebelum akhir bulan.",
                'action'  => null,
            ];
        }

        return null;
    }
}