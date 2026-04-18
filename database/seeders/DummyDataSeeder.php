<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user demo
        $user = User::firstOrCreate(
            ['email' => 'demo@finku.app'],
            [
                'name'     => 'Zakhwan',
                'password' => Hash::make('password123'),
            ]
        );

        // Buat kategori
        $categories = [
            ['name' => 'Uang Jajan',     'type' => 'income',  'color' => '#2e5fba'],
            ['name' => 'Freelance',       'type' => 'income',  'color' => '#059669'],
            ['name' => 'Beasiswa',        'type' => 'income',  'color' => '#7c3aed'],
            ['name' => 'Makan & Minum',   'type' => 'expense', 'color' => '#dc2626'],
            ['name' => 'Transportasi',    'type' => 'expense', 'color' => '#f59e0b'],
            ['name' => 'Pendidikan',      'type' => 'expense', 'color' => '#0891b2'],
            ['name' => 'Hiburan',         'type' => 'expense', 'color' => '#7c3aed'],
            ['name' => 'Kebutuhan Harian','type' => 'expense', 'color' => '#059669'],
            ['name' => 'Pulsa & Internet','type' => 'expense', 'color' => '#ea580c'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Category::firstOrCreate(
                ['user_id' => $user->id, 'name' => $cat['name']],
                ['type' => $cat['type'], 'color' => $cat['color']]
            );
        }

        // Kelompokkan kategori
        $incomeCategories  = array_filter($createdCategories, fn($c) => $c->type === 'income');
        $expenseCategories = array_filter($createdCategories, fn($c) => $c->type === 'expense');
        $incomeCategories  = array_values($incomeCategories);
        $expenseCategories = array_values($expenseCategories);

        // Data transaksi realistis per bulan (6 bulan terakhir)
        $transactionTemplates = [
            // Pemasukan
            ['note' => 'Uang jajan bulanan',       'type' => 'income',  'cat' => 'Uang Jajan',      'amount' => 1500000],
            ['note' => 'Freelance desain logo',     'type' => 'income',  'cat' => 'Freelance',        'amount' => 350000],
            ['note' => 'Freelance edit video',      'type' => 'income',  'cat' => 'Freelance',        'amount' => 250000],
            ['note' => 'Beasiswa PPA',              'type' => 'income',  'cat' => 'Beasiswa',         'amount' => 750000],
            // Pengeluaran
            ['note' => 'Makan siang warteg',        'type' => 'expense', 'cat' => 'Makan & Minum',   'amount' => 15000],
            ['note' => 'Sarapan nasi uduk',         'type' => 'expense', 'cat' => 'Makan & Minum',   'amount' => 12000],
            ['note' => 'Makan malam ayam geprek',   'type' => 'expense', 'cat' => 'Makan & Minum',   'amount' => 20000],
            ['note' => 'Kopi & snack',              'type' => 'expense', 'cat' => 'Makan & Minum',   'amount' => 35000],
            ['note' => 'Grab ke kampus',            'type' => 'expense', 'cat' => 'Transportasi',    'amount' => 18000],
            ['note' => 'Naik angkot',               'type' => 'expense', 'cat' => 'Transportasi',    'amount' => 5000],
            ['note' => 'Bensin motor',              'type' => 'expense', 'cat' => 'Transportasi',    'amount' => 30000],
            ['note' => 'Fotokopi modul',            'type' => 'expense', 'cat' => 'Pendidikan',      'amount' => 25000],
            ['note' => 'Beli buku praktikum',       'type' => 'expense', 'cat' => 'Pendidikan',      'amount' => 85000],
            ['note' => 'Top up game',               'type' => 'expense', 'cat' => 'Hiburan',         'amount' => 50000],
            ['note' => 'Nonton bioskop',            'type' => 'expense', 'cat' => 'Hiburan',         'amount' => 55000],
            ['note' => 'Beli sabun & shampo',       'type' => 'expense', 'cat' => 'Kebutuhan Harian','amount' => 45000],
            ['note' => 'Laundry',                   'type' => 'expense', 'cat' => 'Kebutuhan Harian','amount' => 35000],
            ['note' => 'Beli pulsa',                'type' => 'expense', 'cat' => 'Pulsa & Internet','amount' => 50000],
            ['note' => 'Langganan Spotify',         'type' => 'expense', 'cat' => 'Hiburan',         'amount' => 19000],
        ];

        // Buat kategori lookup
        $categoryLookup = [];
        foreach ($createdCategories as $cat) {
            $categoryLookup[$cat->name] = $cat->id;
        }

        // Generate transaksi 6 bulan terakhir
        Transaction::where('user_id', $user->id)->delete();

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            // Variasi amount per bulan biar lebih realistis
            $multiplier = [0.85, 0.9, 1.0, 0.95, 1.05, 1.1][$i] ?? 1.0;

            foreach ($transactionTemplates as $tmpl) {
                // Random tanggal dalam bulan tersebut
                $day  = rand(1, min(28, $month->daysInMonth));
                $date = Carbon::create($month->year, $month->month, $day);

                // Skip beberapa transaksi secara random biar tidak terlalu seragam
                if (rand(1, 10) <= 2) continue;

                Transaction::create([
                    'user_id'          => $user->id,
                    'category_id'      => $categoryLookup[$tmpl['cat']],
                    'type'             => $tmpl['type'],
                    'amount'           => round($tmpl['amount'] * $multiplier / 1000) * 1000,
                    'note'             => $tmpl['note'],
                    'transaction_date' => $date,
                ]);
            }
        }

        // Set budget untuk bulan ini
        Budget::where('user_id', $user->id)->delete();

        $budgets = [
            'Makan & Minum'    => 600000,
            'Transportasi'     => 200000,
            'Pendidikan'       => 150000,
            'Hiburan'          => 150000,
            'Kebutuhan Harian' => 200000,
            'Pulsa & Internet' => 100000,
        ];

        foreach ($budgets as $catName => $amount) {
            if (isset($categoryLookup[$catName])) {
                Budget::create([
                    'user_id'     => $user->id,
                    'category_id' => $categoryLookup[$catName],
                    'amount'      => $amount,
                    'month'       => now()->month,
                    'year'        => now()->year,
                ]);
            }
        }

        $this->command->info('✅ Demo data berhasil dibuat!');
        $this->command->info('📧 Email  : demo@finku.app');
        $this->command->info('🔑 Password: password123');
    }
}