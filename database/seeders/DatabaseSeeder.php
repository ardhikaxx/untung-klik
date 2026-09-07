<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\CapitalEntry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockMovement;
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
            ['amount' => 1500000, 'source' => 'Tabungan Pribadi', 'description' => 'Tambahan modal bulanan', 'days_ago' => 5],
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

        // Create Product Categories
        $catSembako = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Sembako',
            'description' => 'Bahan pokok kebutuhan sehari-hari',
            'is_active' => true,
        ]);

        $catMinuman = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Minuman Kemasan',
            'description' => 'Aneka minuman dingin dan seduh',
            'is_active' => true,
        ]);

        $catSnack = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Makanan Ringan',
            'description' => 'Camilan, kerupuk, dan biskuit',
            'is_active' => true,
        ]);

        $catBumbu = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Bumbu Dapur',
            'description' => 'Penyedap rasa, kecap, dan saus',
            'is_active' => true,
        ]);

        // Products definitions (safe stock, low stock, out of stock)
        $productsData = [
            [
                'category_id' => $catSembako->id,
                'name' => 'Beras Pandan Wangi 5kg',
                'sku' => 'BRS-PW-05',
                'selling_price' => 78000,
                'purchase_price' => 68000,
                'unit' => 'karung',
                'stock' => 15,
                'min_stock' => 5,
                'description' => 'Beras pulen kualitas super 5 kg',
            ],
            [
                'category_id' => $catSembako->id,
                'name' => 'Minyak Goreng SunCo 2L',
                'sku' => 'MYK-SC-02',
                'selling_price' => 38000,
                'purchase_price' => 33500,
                'unit' => 'pouch',
                'stock' => 24,
                'min_stock' => 6,
                'description' => 'Minyak goreng bening 2 liter',
            ],
            [
                'category_id' => $catSembako->id,
                'name' => 'Gula Pasir Gulaku 1kg',
                'sku' => 'GLA-GL-01',
                'selling_price' => 17500,
                'purchase_price' => 15000,
                'unit' => 'kg',
                'stock' => 3, // Low stock!
                'min_stock' => 5,
                'description' => 'Gula pasir premium 1 kg',
            ],
            [
                'category_id' => $catSembako->id,
                'name' => 'Telur Ayam Ras 1kg',
                'sku' => 'TLR-AY-01',
                'selling_price' => 28000,
                'purchase_price' => 25000,
                'unit' => 'kg',
                'stock' => 0, // Out of stock!
                'min_stock' => 8,
                'description' => 'Telur ayam negeri segar pilihan',
            ],
            [
                'category_id' => $catMinuman->id,
                'name' => 'Teh Botol Sosro 350ml',
                'sku' => 'THB-SS-35',
                'selling_price' => 4500,
                'purchase_price' => 3500,
                'unit' => 'botol',
                'stock' => 36,
                'min_stock' => 12,
                'description' => 'Teh botol rasa original',
            ],
            [
                'category_id' => $catMinuman->id,
                'name' => 'Aqua Air Mineral 600ml',
                'sku' => 'AQU-BT-60',
                'selling_price' => 3500,
                'purchase_price' => 2500,
                'unit' => 'botol',
                'stock' => 4, // Low stock!
                'min_stock' => 10,
                'description' => 'Air minum dalam kemasan botol sedang',
            ],
            [
                'category_id' => $catSnack->id,
                'name' => 'Indomie Goreng Original',
                'sku' => 'MIE-ID-GO',
                'selling_price' => 3500,
                'purchase_price' => 2800,
                'unit' => 'bungkus',
                'stock' => 80,
                'min_stock' => 20,
                'description' => 'Mi instan kuah/goreng legendaris',
            ],
            [
                'category_id' => $catBumbu->id,
                'name' => 'Kecap Bango Manis 520ml',
                'sku' => 'KCP-BG-52',
                'selling_price' => 24000,
                'purchase_price' => 20500,
                'unit' => 'pouch',
                'stock' => 10,
                'min_stock' => 4,
                'description' => 'Kecap kedelai hitam pilihan',
            ],
        ];

        $seededProducts = [];
        foreach ($productsData as $prod) {
            $createdProd = Product::create(array_merge($prod, [
                'business_id' => $business->id,
                'is_active' => true,
            ]));

            // Log initial stock movement
            if ($createdProd->stock > 0) {
                StockMovement::create([
                    'business_id' => $business->id,
                    'product_id' => $createdProd->id,
                    'user_id' => $owner->id,
                    'type' => 'initial',
                    'quantity' => $createdProd->stock,
                    'stock_before' => 0,
                    'stock_after' => $createdProd->stock,
                    'notes' => 'Stok awal sistem',
                ]);
            }

            $seededProducts[] = $createdProd;
        }

        // Seed some sales transactions with transaction_items
        $sampleSales = [
            [
                'user' => $karyawan1,
                'days_ago' => 0, // today
                'items' => [
                    ['product' => $seededProducts[0], 'qty' => 1], // Beras 1
                    ['product' => $seededProducts[4], 'qty' => 2], // Teh Botol 2
                ],
                'source' => 'Pelanggan Toko',
                'payment_method' => 'Tunai',
            ],
            [
                'user' => $karyawan2,
                'days_ago' => 0, // today
                'items' => [
                    ['product' => $seededProducts[6], 'qty' => 5], // Indomie 5
                    ['product' => $seededProducts[1], 'qty' => 1], // Minyak 1
                ],
                'source' => 'Order WhatsApp',
                'payment_method' => 'QRIS/Transfer',
            ],
            [
                'user' => $owner,
                'days_ago' => 1, // yesterday
                'items' => [
                    ['product' => $seededProducts[7], 'qty' => 2], // Kecap 2
                    ['product' => $seededProducts[4], 'qty' => 4], // Teh Botol 4
                ],
                'source' => 'Pembeli Langsung',
                'payment_method' => 'Tunai',
            ],
        ];

        foreach ($sampleSales as $idx => $saleData) {
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($saleData['items'] as $item) {
                $subtotal = $item['product']->selling_price * $item['qty'];
                $totalAmount += $subtotal;
                $itemsToCreate[] = [
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity' => $item['qty'],
                    'unit_price' => $item['product']->selling_price,
                    'purchase_price' => $item['product']->purchase_price,
                    'subtotal' => $subtotal,
                ];
            }

            $date = Carbon::now()->subDays($saleData['days_ago']);
            $invNum = 'PJ-'.$date->format('Ymd').'-'.str_pad($idx + 1, 4, '0', STR_PAD_LEFT);

            $saleTrx = Transaction::create([
                'business_id' => $business->id,
                'user_id' => $saleData['user']->id,
                'category_id' => $catPenjualan->id,
                'type' => 'masuk',
                'amount' => $totalAmount,
                'invoice_number' => $invNum,
                'customer_name' => 'Pelanggan '.$saleData['source'],
                'customer_phone' => '0812'.rand(10000000, 99999999),
                'discount' => 0,
                'cash_received' => $saleData['payment_method'] === 'Tunai' ? ($totalAmount + 10000) : null,
                'cash_change' => $saleData['payment_method'] === 'Tunai' ? 10000 : null,
                'transaction_date' => $date,
                'source' => $saleData['source'],
                'description' => 'Penjualan Produk: '.count($itemsToCreate).' item',
                'payment_method' => $saleData['payment_method'],
                'is_sale' => true,
            ]);

            foreach ($itemsToCreate as $itemRow) {
                $saleTrx->items()->create(array_merge($itemRow, [
                    'business_id' => $business->id,
                ]));

                // Create stock movement for each item
                StockMovement::create([
                    'business_id' => $business->id,
                    'product_id' => $itemRow['product_id'],
                    'user_id' => $saleData['user']->id,
                    'type' => 'sale',
                    'quantity' => -$itemRow['quantity'],
                    'stock_before' => 20,
                    'stock_after' => 20 - $itemRow['quantity'],
                    'notes' => 'Penjualan #'.$invNum,
                    'reference_id' => $saleTrx->id,
                ]);
            }
        }
    }
}
