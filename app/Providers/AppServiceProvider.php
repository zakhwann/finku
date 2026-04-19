<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Debt;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecommendationService
{
    private $user;
    private $now;
    private $daysPassed;
    private $daysInMonth;
    private $daysRemaining;

    public function __construct()
    {
        $this->user          = Auth::user();
        $this->now           = Carbon::now();
        $this->daysPassed    = max($this->now->day, 1);
        $this->daysInMonth   = $this->now->daysInMonth;
        $this->daysRemaining = $this->daysInMonth - $this->daysPassed;
    }

    public function getRecommendations(): array
    {
        $recs = [];

        $recs = array_merge($recs, $this->analyzeBudgets());
        $recs = array_merge($recs, $this->analyzeSavingRate());
        $recs = array_merge($recs, $this->analyzeDebts());
        $recs = array_merge($recs, $this->analyzeNoBudget());

        return $recs;
    }

    private function analyzeBudgets(): array
    {
        $recs    = [];
        $budgets = Budget::where('user_id', $this->user->id)
            ->where('month', $this->now->month)
            ->where('year',  $this->now->year)
            ->with('category')
            ->get();

        foreach ($budgets as $budget) {
            $terpakai = Transaction::where('user_id', $this->user->id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $this->now->month)
                ->whereYear('transaction_date',  $this->now->year)
                ->sum('amount');

            $sisa        = $budget->amount - $terpakai;
            $pct         = $budget->amount > 0 ? round(($terpakai / $budget->amount) * 100) : 0;
            $avgPerDay   = $this->daysPassed > 0 ? $terpakai / $this->daysPassed : 0;
            $proyeksi    = $avgPerDay * $this->daysInMonth;
            $proyeksiPct = $budget->amount > 0 ? round(($proyeksi / $budget->amount) * 100) : 0;
            $catName     = $budget->category->name;
            $sisaFmt     = 'Rp ' . number_format($sisa, 0, ',', '.');
            $proyeksiFmt = 'Rp ' . number_format($proyeksi, 0, ',', '.');

            // OVER BUDGET
            if ($pct >= 100) {
                $recs[] = $this->makeRec('danger',
                    "Over budget — {$catName}",
                    "Kamu sudah melebihi budget kategori ini sebesar Rp " . number_format(abs($sisa), 0, ',', '.') . ". Hindari pengeluaran {$catName} sampai akhir bulan.",
                    null
                );
                continue;
            }

            // HAMPIR HABIS SECARA AKTUAL
            if ($pct >= 80) {
                $recs[] = $this->makeRec('warning',
                    "Budget {$catName} hampir habis ({$pct}%)",
                    "Tersisa {$sisaFmt} untuk {$this->daysRemaining} hari ke depan. Rata-rata aman: Rp " . number_format($sisa / max($this->daysRemaining, 1), 0, ',', '.') . "/hari.",
                    null
                );
                continue;
            }

            // PROYEKSI AKAN OVER meski % sekarang masih kecil
            if ($proyeksiPct >= 100 && $pct < 80) {
                $recs[] = $this->makeRec('warning',
                    "Proyeksi {$catName} akan over budget",
                    "Meski baru {$pct}% terpakai, dengan pola sekarang proyeksi akhir bulan mencapai {$proyeksiFmt} ({$proyeksiPct}% dari budget). Mulai kurangi pengeluaran ini.",
                    null
                );
                continue;
            }

            // PROYEKSI AMAN — berikan rekomendasi positif
            // OVER BUDGET
if ($pct >= 100) {
    $recs[] = $this->makeRec('danger',
        "Over budget — {$catName}",
        "Kamu sudah melebihi budget kategori ini sebesar Rp " . number_format(abs($sisa), 0, ',', '.') . ". Hindari pengeluaran {$catName} sampai akhir bulan.",
        null
    );
    continue;
}

// HAMPIR HABIS AKTUAL (80-99%)
if ($pct >= 80) {
    $recs[] = $this->makeRec('warning',
        "Budget {$catName} hampir habis ({$pct}%)",
        "Tersisa {$sisaFmt} untuk {$this->daysRemaining} hari ke depan. Batas aman per hari: Rp " . number_format($sisa / max($this->daysRemaining, 1), 0, ',', '.') . "/hari.",
        null
    );
    continue;
}

// MENIPIS (50-79%) — range yang sebelumnya tidak tertangkap!
if ($pct >= 50 && $pct < 80) {
    $recs[] = $this->makeRec('warning',
        "Budget {$catName} mulai menipis ({$pct}%)",
        "Sudah separuh lebih budget terpakai. Tersisa {$sisaFmt} — mulai kurangi pengeluaran {$catName} agar tidak over di akhir bulan.",
        null
    );
    continue;
}

// PROYEKSI AKAN OVER meski % sekarang masih kecil
if ($proyeksiPct >= 100 && $pct < 50) {
    $recs[] = $this->makeRec('warning',
        "Proyeksi {$catName} akan over budget",
        "Meski baru {$pct}% terpakai, dengan pola sekarang proyeksi akhir bulan mencapai {$proyeksiFmt} ({$proyeksiPct}% dari budget). Mulai kurangi.",
        null
    );
    continue;
}

// AMAN (< 50% dan proyeksi < 80%)
if ($pct < 50 && $proyeksiPct < 80) {
    $suggestion = $this->getSuggestion(strtolower($catName), $sisa, $proyeksiPct);
    if ($suggestion) {
        $recs[] = $this->makeRec('info',
            $suggestion['title'],
            $suggestion['message'],
            $suggestion['action'] ?? null
        );
    }
}
        }

        return $recs;
    }

    private function analyzeSavingRate(): array
    {
        $recs = [];

        $totalIncome = Transaction::where('user_id', $this->user->id)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $this->now->month)
            ->whereYear('transaction_date',  $this->now->year)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $this->user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $this->now->month)
            ->whereYear('transaction_date',  $this->now->year)
            ->sum('amount');

        if ($totalIncome <= 0) return $recs;

        $savingRate       = round((($totalIncome - $totalExpense) / $totalIncome) * 100);
        $avgExpensePerDay = $this->daysPassed > 0 ? $totalExpense / $this->daysPassed : 0;
        $proyeksiExpense  = $avgExpensePerDay * $this->daysInMonth;
        $proyeksiSaving   = round((($totalIncome - $proyeksiExpense) / $totalIncome) * 100);

        if ($savingRate >= 30) {
            $recs[] = $this->makeRec('success',
                "Saving rate {$savingRate}% — Luar biasa!",
                "Kamu berhasil menyimpan lebih dari 30% pemasukan bulan ini. Proyeksi akhir bulan saving rate kamu sekitar {$proyeksiSaving}%. Pertahankan!",
                null
            );
        } elseif ($savingRate >= 20) {
            $recs[] = $this->makeRec('success',
                "Saving rate {$savingRate}% — Bagus!",
                "Kamu sudah di atas rata-rata. Target saving rate ideal adalah 30%. Coba kurangi sedikit pengeluaran tidak penting untuk mencapainya.",
                null
            );
        } elseif ($savingRate >= 0 && $savingRate < 10) {
            $recs[] = $this->makeRec('warning',
                "Saving rate kamu hanya {$savingRate}%",
                "Proyeksi akhir bulan saving rate kamu sekitar {$proyeksiSaving}%. Targetkan minimal 20% dengan mengurangi pengeluaran tidak esensial.",
                ['label' => 'Lihat Laporan →', 'url' => route('reports.index')]
            );
        } elseif ($savingRate < 0) {
            $recs[] = $this->makeRec('danger',
                'Pengeluaran melebihi pemasukan!',
                "Kamu menghabiskan lebih dari yang kamu dapatkan bulan ini. Proyeksi kerugian akhir bulan: Rp " . number_format(abs($proyeksiExpense - $totalIncome), 0, ',', '.') . ". Segera evaluasi!",
                ['label' => 'Lihat Transaksi →', 'url' => route('transactions.index')]
            );
        }

        return $recs;
    }

    private function analyzeDebts(): array
    {
        $recs     = [];
        $debtsDue = Debt::where('user_id', $this->user->id)
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->get();

        foreach ($debtsDue as $debt) {
            $recs[] = $this->makeRec('danger',
                "Hutang ke {$debt->person_name} jatuh tempo!",
                "Rp " . number_format($debt->remaining, 0, ',', '.') . " jatuh tempo " . $debt->due_date->diffForHumans() . ". Segera selesaikan.",
                ['label' => 'Lihat Hutang →', 'url' => route('debts.index')]
            );
        }

        return $recs;
    }

    private function analyzeNoBudget(): array
    {
        $hasBudget = Budget::where('user_id', $this->user->id)
            ->where('month', $this->now->month)
            ->where('year',  $this->now->year)
            ->exists();

        if (!$hasBudget) {
            return [$this->makeRec('info',
                'Belum ada budget bulan ini',
                'Set budget per kategori untuk mendapatkan rekomendasi pengeluaran yang lebih akurat dan proyeksi keuangan bulanan.',
                ['label' => 'Set Budget →', 'url' => route('budgets.index')]
            )];
        }

        return [];
    }

    private function getSuggestion(string $catName, float $sisa, int $proyeksiPct): ?array
    {
        $sisaFmt = 'Rp ' . number_format($sisa, 0, ',', '.');

        $map = [
            ['keys' => ['makan', 'food', 'minum'],
             'title' => "Budget makan aman — {$sisaFmt} tersisa",
             'msg'   => "Dengan pola sekarang budget makanmu masih aman ({$proyeksiPct}% proyeksi). Boleh sesekali makan di tempat yang sedikit lebih enak!"],

            ['keys' => ['transport', 'bensin', 'ojek'],
             'title' => "Budget transportasi masih sehat",
             'msg'   => "Tersisa {$sisaFmt} dan proyeksi akhir bulan {$proyeksiPct}%. Pertimbangkan transportasi umum untuk menghemat lebih."],

            ['keys' => ['pendidikan', 'buku', 'kursus', 'kuliah'],
             'title' => "Ada {$sisaFmt} untuk investasi pendidikan",
             'msg'   => "Budget pendidikanmu masih lega (proyeksi {$proyeksiPct}%). Ini saat yang tepat untuk beli buku atau ikut kursus online yang bermanfaat.",
             'action'=> null],

            ['keys' => ['hiburan', 'game', 'entertainment', 'nonton'],
             'title' => "Budget hiburan masih {$sisaFmt}",
             'msg'   => "Proyeksi akhir bulan {$proyeksiPct}% — masih aman. Tapi tetap prioritaskan kebutuhan penting ya!"],

            ['keys' => ['pulsa', 'internet', 'kuota'],
             'title' => "Budget komunikasi aman",
             'msg'   => "Tersisa {$sisaFmt} untuk pulsa & internet (proyeksi {$proyeksiPct}%). Pastikan kuota internet cukup sampai akhir bulan."],

            ['keys' => ['kesehatan', 'obat', 'medis'],
             'title' => "Budget kesehatan masih {$sisaFmt}",
             'msg'   => "Bagus! Budget kesehatan sebaiknya selalu dijaga. Gunakan untuk vitamin atau check-up rutin jika perlu."],
        ];

        foreach ($map as $item) {
            foreach ($item['keys'] as $key) {
                if (str_contains($catName, $key)) {
                    return [
                        'title'  => $item['title'],
                        'message'=> $item['msg'],
                        'action' => $item['action'] ?? null,
                    ];
                }
            }
        }

        return null;
    }

    private function makeRec(string $type, string $title, string $message, ?array $action): array
    {
        $styles = [
            'success' => ['color' => '#059669', 'bg' => '#ecfdf5', 'border' => '#a7f3d0', 'icon' => '✓'],
            'info'    => ['color' => '#2e5fba', 'bg' => '#eff4ff', 'border' => '#bfdbfe', 'icon' => '◉'],
            'warning' => ['color' => '#d97706', 'bg' => '#fffbeb', 'border' => '#fde68a', 'icon' => '⚠'],
            'danger'  => ['color' => '#dc2626', 'bg' => '#fef2f2', 'border' => '#fecaca', 'icon' => '!'],
        ];

        $s = $styles[$type] ?? $styles['info'];

        return [
            'type'    => $type,
            'icon'    => $s['icon'],
            'title'   => $title,
            'message' => $message,
            'action'  => $action,
            'color'   => $s['color'],
            'bg'      => $s['bg'],
            'border'  => $s['border'],
        ];
    }
}