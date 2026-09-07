<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create owner user
        $owner = User::create([
            'name' => 'Budi Santoso',
            'username' => 'owner',
            'phone' => '081234567890',
            'pin_hash' => Hash::make('2222'),
            'role' => 'owner',
            'is_active' => true,
            'pin_changed_at' => now(),
        ]);

        // Create business
        $business = Business::create([
            'owner_id' => $owner->id,
            'name' => 'Warung Berkah',
            'type' => 'Warung Kelontong',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Bandung',
            'is_active' => true,
        ]);

        // Update owner with business_id
        $owner->update(['business_id' => $business->id]);

        // Create karyawan users
        $karyawan1 = User::create([
            'business_id' => $business->id,
            'name' => 'Siti Rahayu',
            'username' => 'karyawan1',
            'phone' => '081234567891',
            'pin_hash' => Hash::make('2222'),
            'role' => 'karyawan',
            'is_active' => true,
            'pin_changed_at' => now(),
        ]);

        $karyawan2 = User::create([
            'business_id' => $business->id,
            'name' => 'Andi Wijaya',
            'username' => 'karyawan2',
            'phone' => '081234567892',
            'pin_hash' => Hash::make('2222'),
            'role' => 'karyawan',
            'is_active' => true,
            'pin_changed_at' => now(),
        ]);

        // Create transaction categories
        $catPenjualan = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Penjualan Produk',
            'type' => 'masuk',
            'is_active' => true,
        ]);

        $catJasa = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Pendapatan Jasa',
            'type' => 'masuk',
            'is_active' => true,
        ]);

        $catPembelian = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Pembelian Barang',
            'type' => 'keluar',
            'is_active' => true,
        ]);

        $catGaji = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Gaji Karyawan',
            'type' => 'keluar',
            'is_active' => true,
        ]);

        $catListrik = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Listrik & Air',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        $catSewa = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Sewa Tempat',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        $catInternet = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Internet',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        $catTransport = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Transport',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        // Create capital entries
        $capitalData = [
            ['amount' => 5000000, 'source' => 'Tabungan Pribadi', 'description' => 'Modal awal warung', 'days_ago' => 90],
            ['amount' => 2000000, 'source' => 'Pinjaman Bank', 'description' => 'Tambahan modal untuk stok', 'days_ago' => 60],
            ['amount' => 1500000, 'source' => 'Tabungan Pribadi', 'description' => 'Tambahan modal bulanan', 'days_ago' => 30],
        ];

        foreach ($capitalData as $cap) {
            CapitalEntry::create([
                'business_id' => $business->id,
                'user_id' => $owner->id,
                'amount' => $cap['amount'],
                'entry_date' => Carbon::now()->subDays($cap['days_ago']),
                'source' => $cap['source'],
                'description' => $cap['description'],
            ]);
        }

        // Create transactions (last 30 days)
        $sources = ['Pembeli langsung', 'Order WA', 'Pelanggan tetap', 'Online shop', 'Dropship'];
        $descriptions = ['Penjualan harian', 'Pesanan besar', 'Diskon pelanggan', 'Promo bulanan', ''];

        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            $dailyCount = rand(2, 5);

            for ($j = 0; $j < $dailyCount; $j++) {
                $type = rand(1, 10) > 3 ? 'masuk' : 'keluar';
                $user = $type === 'masuk' ? ($j === 0 ? $karyawan1 : $karyawan2) : $owner;
                $category = $type === 'masuk' ? $catPenjualan : $catPembelian;

                Transaction::create([
                    'business_id' => $business->id,
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'type' => $type,
                    'amount' => $type === 'masuk' ? rand(50000, 500000) : rand(30000, 300000),
                    'transaction_date' => $date,
                    'source' => $sources[array_rand($sources)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'payment_method' => rand(1, 10) > 5 ? 'Tunai' : 'Transfer',
                ]);
            }
        }

        // Create operational expenses (last 30 days)
        $expenseData = [
            ['category' => $catListrik, 'amount' => 350000, 'desc' => 'Tagihan listrik bulanan'],
            ['category' => $catSewa, 'amount' => 2000000, 'desc' => 'Sewa tempat bulanan'],
            ['category' => $catInternet, 'amount' => 150000, 'desc' => 'Paket internet bulanan'],
            ['category' => $catTransport, 'amount' => 50000, 'desc' => 'Biaya antar barang'],
            ['category' => $catTransport, 'amount' => 35000, 'desc' => 'Transport ke supplier'],
            ['category' => $catListrik, 'amount' => 350000, 'desc' => 'Tagihan listrik bulan lalu'],
            ['category' => $catSewa, 'amount' => 2000000, 'desc' => 'Sewa tempat bulan lalu'],
            ['category' => $catInternet, 'amount' => 150000, 'desc' => 'Paket internet bulan lalu'],
        ];

        foreach ($expenseData as $idx => $exp) {
            OperationalExpense::create([
                'business_id' => $business->id,
                'user_id' => $owner->id,
                'category_id' => $exp['category']->id,
                'amount' => $exp['amount'],
                'expense_date' => Carbon::now()->subDays($idx * 10),
                'description' => $exp['desc'],
            ]);
        }
    }
}
