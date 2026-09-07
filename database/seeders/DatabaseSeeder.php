<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
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
        // 1. Create Owner User
        $owner = User::create([
            'name' => 'Budi Santoso',
            'username' => 'owner',
            'phone' => '081234567890',
            'pin_hash' => Hash::make('2222'),
            'role' => 'owner',
            'is_active' => true,
            'pin_changed_at' => now(),
        ]);

        // 2. Create Business (Dealer Resmi Uwinfly & NUV)
        $business = Business::create([
            'owner_id' => $owner->id,
            'name' => 'Galeri E-Bike Uwinfly & NUV',
            'type' => 'Dealer Resmi Sepeda & Motor Listrik',
            'phone' => '081234567890',
            'address' => 'Jl. Soekarno-Hatta No. 210, Bandung',
            'is_active' => true,
        ]);

        $owner->update(['business_id' => $business->id]);

        // 3. Create Karyawan Users (Kasir & Teknisi)
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

        // 4. Create Transaction Categories
        $catPenjualan = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Penjualan Produk',
            'type' => 'masuk',
            'is_active' => true,
        ]);

        $catJasa = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Jasa Servis & Ganti Aki',
            'type' => 'masuk',
            'is_active' => true,
        ]);

        $catAntar = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Jasa Antar Unit E-Bike',
            'type' => 'masuk',
            'is_active' => true,
        ]);

        $catPendapatanLain = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Pemasukan Lainnya',
            'type' => 'masuk',
            'is_active' => true,
        ]);

        $catRestock = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Kulakan Unit Uwinfly & NUV',
            'type' => 'keluar',
            'is_active' => true,
        ]);

        $catGaji = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Gaji Karyawan & Teknisi',
            'type' => 'keluar',
            'is_active' => true,
        ]);

        $catListrik = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Listrik & Fast Charging Showroom',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        $catSewa = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Sewa Ruko & Showroom Dealer',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        $catInternet = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Internet & Iklan Medsos',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        $catBensin = TransactionCategory::create([
            'business_id' => $business->id,
            'name' => 'Bensin & Operasional Pick-Up',
            'type' => 'operasional',
            'is_active' => true,
        ]);

        // 5. Create Capital Entries
        $capitalData = [
            [
                'amount' => 150000000,
                'source' => 'Tabungan Pribadi',
                'description' => 'Modal awal pendirian showroom dan bengkel dealer resmi Uwinfly & NUV',
                'days_ago' => 90,
            ],
            [
                'amount' => 60000000,
                'source' => 'Kredit Usaha Bank',
                'description' => 'Pengadaan stok kontainer pertama unit sepeda listrik Uwinfly dan NUV PMB',
                'days_ago' => 60,
            ],
            [
                'amount' => 25000000,
                'source' => 'Tabungan Pribadi',
                'description' => 'Penambahan modal stok baterai aki kering original dan suku cadang fast moving',
                'days_ago' => 15,
            ],
        ];

        foreach ($capitalData as $cap) {
            CapitalEntry::create([
                'business_id' => $business->id,
                'user_id' => $owner->id,
                'amount' => $cap['amount'],
                'entry_date' => Carbon::now()->subDays($cap['days_ago'])->toDateString(),
                'source' => $cap['source'],
                'description' => $cap['description'],
            ]);
        }

        // 6. Create Operational Expenses
        $expenseRecords = [
            ['cat' => $catSewa, 'amount' => 3800000, 'desc' => 'Sewa ruko showroom dealer Uwinfly & NUV bulan ini', 'days' => 5],
            ['cat' => $catListrik, 'amount' => 950000, 'desc' => 'Tagihan listrik 4400VA dan charging station showroom', 'days' => 8],
            ['cat' => $catInternet, 'amount' => 380000, 'desc' => 'Paket internet fiber optik dan iklan promosi e-bike', 'days' => 10],
            ['cat' => $catBensin, 'amount' => 180000, 'desc' => 'Bensin mobil pick-up antar 4 unit Uwinfly & NUV ke konsumen', 'days' => 12],
            ['cat' => $catBensin, 'amount' => 160000, 'desc' => 'Bensin kirim unit pesanan ke Cimahi dan Banjaran', 'days' => 22],
            ['cat' => $catSewa, 'amount' => 3800000, 'desc' => 'Sewa ruko showroom dealer bulan lalu', 'days' => 35],
            ['cat' => $catListrik, 'amount' => 910000, 'desc' => 'Tagihan listrik bulan lalu', 'days' => 38],
            ['cat' => $catInternet, 'amount' => 380000, 'desc' => 'Paket internet dan iklan online bulan lalu', 'days' => 40],
        ];

        foreach ($expenseRecords as $exp) {
            OperationalExpense::create([
                'business_id' => $business->id,
                'user_id' => $owner->id,
                'category_id' => $exp['cat']->id,
                'amount' => $exp['amount'],
                'expense_date' => Carbon::now()->subDays($exp['days'])->toDateString(),
                'description' => $exp['desc'],
            ]);
        }

        // 7. Create Product Categories focused on Uwinfly and NUV
        $catUwinflyEbike = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Sepeda Listrik Uwinfly',
            'description' => 'Lini sepeda listrik terlaris merk Uwinfly (Seri D, R, M, dan DF)',
            'is_active' => true,
        ]);

        $catNuvEbike = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Sepeda Listrik NUV (PMB)',
            'description' => 'Lini sepeda listrik terbaru dan modern merk NUV by PMB Toys (Seri S dan F)',
            'is_active' => true,
        ]);

        $catUwinflyRetro = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Motor & Skuter Listrik Uwinfly',
            'description' => 'Skuter listrik retro Vespa style dan motor listrik sporty merk Uwinfly',
            'is_active' => true,
        ]);

        $catUwinflyRodaTiga = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Sepeda Listrik Roda Tiga Uwinfly',
            'description' => 'Tricycle e-bike Uwinfly ekstra stabil untuk lansia, ibu-ibu, dan angkut muatan',
            'is_active' => true,
        ]);

        $catBateraiCharger = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Baterai & Charger Uwinfly & NUV',
            'description' => 'Aki kering original pabrikan Uwinfly dan NUV PMB serta fast charger otomatis',
            'is_active' => true,
        ]);

        $catSparepartAksesoris = ProductCategory::create([
            'business_id' => $business->id,
            'name' => 'Sparepart & Aksesoris Resmi',
            'description' => 'Suku cadang asli, kartu smart key NFC, ban tubeless, dan perlengkapan berkendara',
            'is_active' => true,
        ]);

        // 8. Authentic Real-World Products Catalog: Uwinfly & NUV (52 Real Products)
        $productsData = [
            // --- Kategori 1: Sepeda Listrik Uwinfly (12 Produk) ---
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly D7S',
                'sku' => 'UWF-D7S-RED',
                'selling_price' => 3950000,
                'purchase_price' => 3300000,
                'unit' => 'unit',
                'stock' => 14,
                'min_stock' => 3,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Fitur Smart NFC Tap Card, Speedometer digital, Daya angkut 150 kg, Kecepatan 35 km/jam',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly D8S',
                'sku' => 'UWF-D8S-BLK',
                'selling_price' => 4100000,
                'purchase_price' => 3450000,
                'unit' => 'unit',
                'stock' => 10,
                'min_stock' => 3,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Bagasi bawah jok luas, Footstep lebar, Lampu proyektor LED tajam',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly D8P New Edition',
                'sku' => 'UWF-D8P-BLU',
                'selling_price' => 4200000,
                'purchase_price' => 3500000,
                'unit' => 'unit',
                'stock' => 8,
                'min_stock' => 3,
                'description' => 'Model terbaru Uwinfly D8P, Motor 500W, Baterai 48V 12Ah, Suspensi ganda hidrolik, Remote keyless',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly Dragonfly 8 (DF8)',
                'sku' => 'UWF-DF8-GRY',
                'selling_price' => 4250000,
                'purchase_price' => 3550000,
                'unit' => 'unit',
                'stock' => 12,
                'min_stock' => 4,
                'description' => 'Motor 600W bertenaga nanjak, Baterai 48V 12Ah, Shockbreaker empuk, Sandaran belakang nyaman',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly Dragonfly 7 (DF7)',
                'sku' => 'UWF-DF7-WHT',
                'selling_price' => 3800000,
                'purchase_price' => 3150000,
                'unit' => 'unit',
                'stock' => 7,
                'min_stock' => 3,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Desain ramping sporty, Ban tubeless 14 x 2.50',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly Redfish D7D',
                'sku' => 'UWF-RFD-7D',
                'selling_price' => 3600000,
                'purchase_price' => 3000000,
                'unit' => 'unit',
                'stock' => 9,
                'min_stock' => 3,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Bobot ringan dan sangat praktis untuk anak sekolah dan belanja harian',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly R7S',
                'sku' => 'UWF-R7S-ORG',
                'selling_price' => 4150000,
                'purchase_price' => 3480000,
                'unit' => 'unit',
                'stock' => 6,
                'min_stock' => 3,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Desain tajam sporty gaya racing, Speedometer LED color',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly R8S',
                'sku' => 'UWF-R8S-YLW',
                'selling_price' => 4350000,
                'purchase_price' => 3650000,
                'unit' => 'unit',
                'stock' => 5,
                'min_stock' => 2,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Rangka kokoh, Desain modern generasi penerus R7S',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly R8P',
                'sku' => 'UWF-R8P-BLK',
                'selling_price' => 4450000,
                'purchase_price' => 3750000,
                'unit' => 'unit',
                'stock' => 4,
                'min_stock' => 2,
                'description' => 'Motor 600W, Baterai 48V 12Ah, Fitur Smart NFC Card, Rem cakram depan, Suspensi nyaman',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly M60',
                'sku' => 'UWF-M60-PST',
                'selling_price' => 4350000,
                'purchase_price' => 3650000,
                'unit' => 'unit',
                'stock' => 8,
                'min_stock' => 3,
                'description' => 'Motor 500W, Baterai 48V 12Ah, Bodi membulat elegan, Dilengkapi port USB charging smartphone',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly M70',
                'sku' => 'UWF-M70-RED',
                'selling_price' => 4500000,
                'purchase_price' => 3800000,
                'unit' => 'unit',
                'stock' => 2, // Low stock
                'min_stock' => 3,
                'description' => 'Model premium M-Series, Motor 500W, Baterai 48V 12Ah, Lampu sein sequential LED (Stok Menipis)',
            ],
            [
                'category_id' => $catUwinflyEbike->id,
                'name' => 'Uwinfly B5 Cargo',
                'sku' => 'UWF-B50-CRG',
                'selling_price' => 4850000,
                'purchase_price' => 4100000,
                'unit' => 'unit',
                'stock' => 0, // Out of stock
                'min_stock' => 2,
                'description' => 'Sepeda listrik kargo khusus angkut barang/galon/gas dengan jok belakang rata (Stok Habis)',
            ],

            // --- Kategori 2: Sepeda Listrik NUV by PMB (10 Produk) ---
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S5 NAVER (White Pearl)',
                'sku' => 'NUV-S5-WHT',
                'selling_price' => 4750000,
                'purchase_price' => 4000000,
                'unit' => 'unit',
                'stock' => 12,
                'min_stock' => 3,
                'description' => 'Flagship Best Seller NUV! Motor 850 Watt bertenaga tinggi, Baterai 48V 12Ah, Smart Key NFC, Kecepatan 45 km/jam, Jarak 40 km',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S5 NAVER (Sage Green)',
                'sku' => 'NUV-S5-SGE',
                'selling_price' => 4750000,
                'purchase_price' => 4000000,
                'unit' => 'unit',
                'stock' => 9,
                'min_stock' => 3,
                'description' => 'Warna favorit kekinian Sage Green, Motor 850W, Baterai 48V 12Ah, NFC keyless, Keranjang modern kokoh',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S5 NAVER (Midnight Black)',
                'sku' => 'NUV-S5-BLK',
                'selling_price' => 4750000,
                'purchase_price' => 4000000,
                'unit' => 'unit',
                'stock' => 8,
                'min_stock' => 3,
                'description' => 'Warna hitam doff elegan, Motor 850W, Baterai 48V 12Ah, Rem cakram depan, Spion modern',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S1 GLOBBER',
                'sku' => 'NUV-S1-GLB',
                'selling_price' => 4350000,
                'purchase_price' => 3650000,
                'unit' => 'unit',
                'stock' => 7,
                'min_stock' => 3,
                'description' => 'Motor 850 Watt, Baterai 48V 12Ah, Desain futuristik aerodinamis, Lampu full LED proyektor',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S2 NYRA',
                'sku' => 'NUV-S2-NYR',
                'selling_price' => 4450000,
                'purchase_price' => 3750000,
                'unit' => 'unit',
                'stock' => 6,
                'min_stock' => 3,
                'description' => 'Motor 850 Watt, Baterai 48V 12Ah, Bodi ramping feminin sangat cocok untuk remaja & wanita',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S3 FLOPPY',
                'sku' => 'NUV-S3-FLP',
                'selling_price' => 4550000,
                'purchase_price' => 3850000,
                'unit' => 'unit',
                'stock' => 8,
                'min_stock' => 3,
                'description' => 'Motor 850 Watt, Baterai 48V 12Ah, Rangka hi-ten steel PMB kuat, Suspensi empuk peredam getaran',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S6 SAVOX',
                'sku' => 'NUV-S6-SVX',
                'selling_price' => 4850000,
                'purchase_price' => 4100000,
                'unit' => 'unit',
                'stock' => 5,
                'min_stock' => 2,
                'description' => 'Motor 850 Watt, Baterai 48V 12Ah, Desain gagah maskulin, Pijakan kaki ekstra luas, Ban 14 x 2.50',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV S7 VERITON',
                'sku' => 'NUV-S7-VRT',
                'selling_price' => 5150000,
                'purchase_price' => 4350000,
                'unit' => 'unit',
                'stock' => 2, // Low stock
                'min_stock' => 3,
                'description' => 'Tipe kasta tertinggi NUV S-Series! Motor 850 Watt, Rem cakram hidrolik, Rangka premium (Stok Menipis)',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV F1 SPIVA (Lemon Yellow)',
                'sku' => 'NUV-F1-YEL',
                'selling_price' => 4150000,
                'purchase_price' => 3500000,
                'unit' => 'unit',
                'stock' => 6,
                'min_stock' => 2,
                'description' => 'Motor 650 Watt efisien, Baterai 48V 12Ah, Warna cerah ceria, Ringan dan lincah bermanuver',
            ],
            [
                'category_id' => $catNuvEbike->id,
                'name' => 'NUV F1 SPIVA (Pastel Blue)',
                'sku' => 'NUV-F1-BLU',
                'selling_price' => 4150000,
                'purchase_price' => 3500000,
                'unit' => 'unit',
                'stock' => 0, // Out of stock
                'min_stock' => 2,
                'description' => 'Motor 650 Watt, Baterai 48V 12Ah, Desain compact minimalis (Stok Habis)',
            ],

            // --- Kategori 3: Motor & Skuter Listrik Uwinfly (5 Produk) ---
            [
                'category_id' => $catUwinflyRetro->id,
                'name' => 'Uwinfly T3S Pro Retro Vespa (White)',
                'sku' => 'UWF-T3S-WHT',
                'selling_price' => 9800000,
                'purchase_price' => 8400000,
                'unit' => 'unit',
                'stock' => 4,
                'min_stock' => 2,
                'description' => 'Skuter listrik desain legendaris Vespa Italia! Motor 1200W, Baterai 60V 20Ah, Kecepatan 60 km/jam, Rem cakram depan, Spion bulat krom',
            ],
            [
                'category_id' => $catUwinflyRetro->id,
                'name' => 'Uwinfly T3S Pro Retro Vespa (Matte Black)',
                'sku' => 'UWF-T3S-BLK',
                'selling_price' => 9800000,
                'purchase_price' => 8400000,
                'unit' => 'unit',
                'stock' => 3,
                'min_stock' => 2,
                'description' => 'Warna hitam doff garang klasik, Motor 1200W, Baterai 60V 20Ah, Jok kulit sintetis cokelat',
            ],
            [
                'category_id' => $catUwinflyRetro->id,
                'name' => 'Uwinfly T3S Pro Retro Vespa (Mint Green)',
                'sku' => 'UWF-T3S-MNT',
                'selling_price' => 9800000,
                'purchase_price' => 8400000,
                'unit' => 'unit',
                'stock' => 2, // Low stock
                'min_stock' => 2,
                'description' => 'Warna hijau tosca pastel retro, Motor 1200W, Baterai 60V 20Ah, Lampu utama LED heksagonal',
            ],
            [
                'category_id' => $catUwinflyRetro->id,
                'name' => 'Uwinfly T5 Sport Retro',
                'sku' => 'UWF-T50-SP',
                'selling_price' => 12500000,
                'purchase_price' => 10800000,
                'unit' => 'unit',
                'stock' => 3,
                'min_stock' => 1,
                'description' => 'Motor listrik tenaga buas 2000 Watt, Baterai 72V 20Ah, Double disc brake, Top speed 70 km/jam',
            ],
            [
                'category_id' => $catUwinflyRetro->id,
                'name' => 'Uwinfly N9 Pro Racing Style',
                'sku' => 'UWF-N9P-RCG',
                'selling_price' => 13500000,
                'purchase_price' => 11600000,
                'unit' => 'unit',
                'stock' => 2,
                'min_stock' => 1,
                'description' => 'Motor listrik desain sport maxi racing, Motor 2000 Watt, Baterai 72V 32Ah jarak tempuh 80 km',
            ],

            // --- Kategori 4: Sepeda Listrik Roda Tiga Uwinfly (3 Produk) ---
            [
                'category_id' => $catUwinflyRodaTiga->id,
                'name' => 'Uwinfly Kitty Roda Tiga (Ruby Red)',
                'sku' => 'UWF-KTY-RED',
                'selling_price' => 7650000,
                'purchase_price' => 6500000,
                'unit' => 'unit',
                'stock' => 3,
                'min_stock' => 1,
                'description' => 'Tricycle e-bike roda 3 super stabil, Motor 500W, Baterai 48V 20Ah, Gigi mundur, Jok geser muat anak, Sangat aman untuk lansia',
            ],
            [
                'category_id' => $catUwinflyRodaTiga->id,
                'name' => 'Uwinfly Kitty Roda Tiga (Sky Blue)',
                'sku' => 'UWF-KTY-BLU',
                'selling_price' => 7650000,
                'purchase_price' => 6500000,
                'unit' => 'unit',
                'stock' => 1, // Low stock
                'min_stock' => 2,
                'description' => 'Warna biru muda cerah, Roda 3 anti-terbalik, Keranjang depan & bagasi belakang luas',
            ],
            [
                'category_id' => $catUwinflyRodaTiga->id,
                'name' => 'Uwinfly Maleo Kargo Roda Tiga',
                'sku' => 'UWF-MLO-KRG',
                'selling_price' => 9900000,
                'purchase_price' => 8500000,
                'unit' => 'unit',
                'stock' => 2,
                'min_stock' => 1,
                'description' => 'Sepeda listrik kargo roda 3 bak terbuka kokoh, Motor 1000W bertenaga, Daya angkut 250 kg',
            ],

            // --- Kategori 5: Baterai & Charger Uwinfly & NUV (8 Produk) ---
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Aki Kering Original Uwinfly 48V 12Ah (Set 4 Pcs)',
                'sku' => 'AKI-UWF-4812',
                'selling_price' => 950000,
                'purchase_price' => 780000,
                'unit' => 'set',
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Baterai aki kering SLA Graphene resmi Uwinfly untuk tipe D7S, D8S, DF8, R7S, M60 (1 set isi 4 unit 12V 12Ah)',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Aki Kering Original Uwinfly 60V 20Ah (Set 5 Pcs)',
                'sku' => 'AKI-UWF-6020',
                'selling_price' => 1850000,
                'purchase_price' => 1550000,
                'unit' => 'set',
                'stock' => 5,
                'min_stock' => 2,
                'description' => 'Set 5 baterai original Uwinfly khusus skuter listrik T3, T3S Pro, dan T5',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Aki Kering Original NUV PMB 48V 12Ah (Set 4 Pcs)',
                'sku' => 'AKI-NUV-4812',
                'selling_price' => 980000,
                'purchase_price' => 800000,
                'unit' => 'set',
                'stock' => 16,
                'min_stock' => 4,
                'description' => 'Baterai resmi PMB untuk sepeda listrik NUV S1, S2, S3, S5 Naver, S6, S7, dan F1 Spiva (1 set 4 unit)',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Baterai Lithium Ion E-Bike 48V 20Ah + Smart BMS',
                'sku' => 'BAT-LIT-4820',
                'selling_price' => 3200000,
                'purchase_price' => 2650000,
                'unit' => 'unit',
                'stock' => 4,
                'min_stock' => 2,
                'description' => 'Upgrade baterai lithium ringan hanya 6 kg untuk Uwinfly & NUV, Umur pakai hingga 4-5 tahun',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Smart Charger Original Uwinfly 48V 12Ah Auto-Cut',
                'sku' => 'CHG-UWF-4812',
                'selling_price' => 145000,
                'purchase_price' => 95000,
                'unit' => 'pcs',
                'stock' => 30,
                'min_stock' => 6,
                'description' => 'Charger asli Uwinfly dengan kipas pendingin otomatis cut-off saat baterai penuh anti kembung',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Smart Charger Original Uwinfly 60V 20Ah Fast Charge',
                'sku' => 'CHG-UWF-6020',
                'selling_price' => 225000,
                'purchase_price' => 165000,
                'unit' => 'pcs',
                'stock' => 15,
                'min_stock' => 4,
                'description' => 'Charger resmi motor listrik Uwinfly T3/T5 soket 3 pin model komputer',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Smart Charger Original NUV PMB 48V 12Ah Auto-Cut',
                'sku' => 'CHG-NUV-4812',
                'selling_price' => 150000,
                'purchase_price' => 100000,
                'unit' => 'pcs',
                'stock' => 25,
                'min_stock' => 5,
                'description' => 'Charger resmi PMB NUV dengan proteksi overcharge dan korsleting',
            ],
            [
                'category_id' => $catBateraiCharger->id,
                'name' => 'Smart Charger Fast Charging 48V 20Ah Universal',
                'sku' => 'CHG-FST-4820',
                'selling_price' => 180000,
                'purchase_price' => 125000,
                'unit' => 'pcs',
                'stock' => 18,
                'min_stock' => 4,
                'description' => 'Arus pengisian 3A cepat untuk baterai kapasitas besar Uwinfly Roda 3 dan NUV S7',
            ],

            // --- Kategori 6: Sparepart & Aksesoris Resmi Uwinfly & NUV (14 Produk) ---
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Ban Luar Tubeless Original Uwinfly 14 x 2.50',
                'sku' => 'BAN-UWF-1425',
                'selling_price' => 115000,
                'purchase_price' => 85000,
                'unit' => 'pcs',
                'stock' => 35,
                'min_stock' => 8,
                'description' => 'Ban tubeless original Uwinfly anti bocor kompon tebal awet jalan aspal/berbatu',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Ban Luar Tubeless Uwinfly T3 10 x 3.00 (Vespa)',
                'sku' => 'BAN-UWF-1030',
                'selling_price' => 145000,
                'purchase_price' => 110000,
                'unit' => 'pcs',
                'stock' => 18,
                'min_stock' => 5,
                'description' => 'Ban donat tubeless lebar untuk skuter listrik Uwinfly T3, T3S, dan T5',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Ban Luar Tubeless Original NUV PMB 14 x 2.50',
                'sku' => 'BAN-NUV-1425',
                'selling_price' => 120000,
                'purchase_price' => 88000,
                'unit' => 'pcs',
                'stock' => 28,
                'min_stock' => 6,
                'description' => 'Ban tubeless resmi bawaan pabrik PMB untuk NUV Seri S dan F',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kartu Smart Key NFC Uwinfly Original (Set 2 Pcs)',
                'sku' => 'NFC-UWF-02',
                'selling_price' => 50000,
                'purchase_price' => 25000,
                'unit' => 'set',
                'stock' => 40,
                'min_stock' => 10,
                'description' => 'Kartu tap cadangan / duplikat resmi untuk Uwinfly D7S, D8P, R8P',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kartu Smart Key NFC NUV PMB Original (Set 2 Pcs)',
                'sku' => 'NFC-NUV-02',
                'selling_price' => 55000,
                'purchase_price' => 28000,
                'unit' => 'set',
                'stock' => 35,
                'min_stock' => 10,
                'description' => 'Kartu tap duplikat smart key resmi NUV S1, S2, S5 Naver, S7',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Remote Alarm Keyless Uwinfly Universal (Sepasang)',
                'sku' => 'RMT-UWF-01',
                'selling_price' => 85000,
                'purchase_price' => 50000,
                'unit' => 'set',
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Remote kunci kontak nirkabel Uwinfly tombol lock, unlock, starter & bell',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kontroller BLDC Original Uwinfly 48V 500W-600W',
                'sku' => 'KNT-UWF-500',
                'selling_price' => 235000,
                'purchase_price' => 170000,
                'unit' => 'pcs',
                'stock' => 12,
                'min_stock' => 4,
                'description' => 'Modul ECU kontroller original Uwinfly socket PNP tanpa potong kabel',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kontroller BLDC Original NUV PMB 48V 850W',
                'sku' => 'KNT-NUV-850',
                'selling_price' => 265000,
                'purchase_price' => 195000,
                'unit' => 'pcs',
                'stock' => 10,
                'min_stock' => 3,
                'description' => 'Modul ECU kontroller resmi NUV tenaga 850W responsif',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kampas Rem Tromol Belakang Uwinfly & NUV',
                'sku' => 'REM-TRM-UWF',
                'selling_price' => 45000,
                'purchase_price' => 25000,
                'unit' => 'set',
                'stock' => 50,
                'min_stock' => 10,
                'description' => 'Brake shoe tromol diameter 110mm pakem dan tidak berdecit',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kampas Rem Cakram Depan NUV & Uwinfly (Disc Pad)',
                'sku' => 'REM-CKR-NUV',
                'selling_price' => 55000,
                'purchase_price' => 32000,
                'unit' => 'set',
                'stock' => 40,
                'min_stock' => 10,
                'description' => 'Brake pad hidrolik cakram depan Uwinfly T3/DF8 dan NUV S5/S7',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Kaca Spion Retro Krom Uwinfly T3 (Sepasang)',
                'sku' => 'SPN-UWF-T3',
                'selling_price' => 60000,
                'purchase_price' => 35000,
                'unit' => 'pasang',
                'stock' => 25,
                'min_stock' => 5,
                'description' => 'Spion bulat cermin cembung tiang krom retro untuk Uwinfly T3 dan T5',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Helm Retro Half Face SNI Eksklusif Uwinfly',
                'sku' => 'HLM-UWF-RTR',
                'selling_price' => 125000,
                'purchase_price' => 88000,
                'unit' => 'pcs',
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Helm bogo retro logo Uwinfly standar SNI kaca cembung pelindung debu',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Jas Hujan Ponco 2 Kepala Khusus E-Bike Uwinfly & NUV',
                'sku' => 'JAS-HJC-EBIKE',
                'selling_price' => 85000,
                'purchase_price' => 55000,
                'unit' => 'pcs',
                'stock' => 26,
                'min_stock' => 6,
                'description' => 'Bahan tebal anti rembes dengan penutup lampu depan transparan agar lampu tetap terang saat hujan',
            ],
            [
                'category_id' => $catSparepartAksesoris->id,
                'name' => 'Gembok Alarm Rem Cakram Anti Maling E-Bike',
                'sku' => 'GBK-ALR-DISC',
                'selling_price' => 80000,
                'purchase_price' => 50000,
                'unit' => 'pcs',
                'stock' => 30,
                'min_stock' => 6,
                'description' => 'Gembok disc brake sensor getar sirene 110dB keras anti curi sepeda',
            ],
        ];

        $seededProducts = [];
        foreach ($productsData as $prod) {
            $createdProd = Product::create(array_merge($prod, [
                'business_id' => $business->id,
                'is_active' => true,
            ]));

            // Initial stock audit movement
            if ($createdProd->stock > 0) {
                StockMovement::create([
                    'business_id' => $business->id,
                    'product_id' => $createdProd->id,
                    'user_id' => $owner->id,
                    'type' => 'initial',
                    'quantity' => $createdProd->stock,
                    'stock_before' => 0,
                    'stock_after' => $createdProd->stock,
                    'notes' => 'Penerimaan stok awal sistem showroom dealer',
                ]);
            }

            $seededProducts[] = $createdProd;
        }

        // 9. Create Non-Product Manual Cash In & Out (Bookkeeping last 30 days)
        $manualServices = [
            ['source' => 'Pak Hendra (Servis Uwinfly)', 'desc' => 'Jasa tune-up kelistrikan & setting rem Uwinfly D7S', 'amount' => 75000, 'cat' => $catJasa],
            ['source' => 'Ibu Nurul (Ganti Aki NUV)', 'desc' => 'Jasa pasang aki 48V & balancing cell NUV S5 Naver', 'amount' => 50000, 'cat' => $catJasa],
            ['source' => 'Pak Bambang (Antar Unit)', 'desc' => 'Ongkir antar 1 unit Uwinfly T3S Pro ke Cimahi', 'amount' => 100000, 'cat' => $catAntar],
            ['source' => 'Ibu Dewi (Ganti Ban)', 'desc' => 'Jasa pasang ban tubeless Swallow + cairan anti bocor', 'amount' => 35000, 'cat' => $catJasa],
            ['source' => 'Mas Rizky (Setting NFC)', 'desc' => 'Jasa pairing kartu Smart Key NFC baru NUV S5', 'amount' => 40000, 'cat' => $catJasa],
            ['source' => 'Pelanggan Walk-in', 'desc' => 'Tip teknisi perbaikan kabel gas putus', 'amount' => 25000, 'cat' => $catPendapatanLain],
            ['source' => 'Pak Gunawan (Antar Unit)', 'desc' => 'Ongkir antar 1 unit Uwinfly Kitty Roda 3 ke Soreang', 'amount' => 150000, 'cat' => $catAntar],
            ['source' => 'Bu Hj. Siti (Servis Berkala)', 'desc' => 'Pengecekan baterai aki, dinamo BLDC dan rem tromol', 'amount' => 50000, 'cat' => $catJasa],
        ];

        foreach ($manualServices as $serv) {
            $daysAgo = rand(0, 25);
            $handler = rand(0, 1) === 0 ? $karyawan1 : $karyawan2;
            Transaction::create([
                'business_id' => $business->id,
                'user_id' => $handler->id,
                'category_id' => $serv['cat']->id,
                'type' => 'masuk',
                'is_sale' => false,
                'amount' => $serv['amount'],
                'transaction_date' => Carbon::now()->subDays($daysAgo)->toDateString(),
                'source' => $serv['source'],
                'description' => $serv['desc'],
                'payment_method' => rand(0, 1) === 0 ? 'Tunai' : 'QRIS',
            ]);
        }

        // Restock Outflow Transactions
        Transaction::create([
            'business_id' => $business->id,
            'user_id' => $owner->id,
            'category_id' => $catRestock->id,
            'type' => 'keluar',
            'is_sale' => false,
            'amount' => 42000000,
            'transaction_date' => Carbon::now()->subDays(20)->toDateString(),
            'source' => 'Pabrik Uwinfly Indonesia',
            'description' => 'Pembelian restock 12 unit Uwinfly D7S, D8S, DF8, dan T3S Pro',
            'payment_method' => 'Transfer',
        ]);

        Transaction::create([
            'business_id' => $business->id,
            'user_id' => $owner->id,
            'category_id' => $catRestock->id,
            'type' => 'keluar',
            'is_sale' => false,
            'amount' => 28000000,
            'transaction_date' => Carbon::now()->subDays(10)->toDateString(),
            'source' => 'Distributor Resmi PMB Toys NUV',
            'description' => 'Pembelian restock 7 unit NUV S5 Naver, S1 Globber, dan S3 Floppy',
            'payment_method' => 'Transfer',
        ]);

        // 10. Generate Rich POS Sales Transactions (Last 30 Days)
        $customerList = [
            ['name' => 'Ibu Ratna Juwita', 'phone' => '081298765432'],
            ['name' => 'Pak Hendra Gunawan', 'phone' => '081345678912'],
            ['name' => 'Ibu Nurul Aisyah', 'phone' => '081234567899'],
            ['name' => 'Pak Agus Salim', 'phone' => '085712345678'],
            ['name' => 'Bu Hj. Siti Aminah', 'phone' => '081987654321'],
            ['name' => 'Mas Rizky Pratama', 'phone' => '082134567890'],
            ['name' => 'Pak Bambang Pamungkas', 'phone' => '081324354657'],
            ['name' => 'Ibu Dewi Sartika', 'phone' => '087812345678'],
            ['name' => 'Pak Wahyu Hidayat', 'phone' => '081267890123'],
            ['name' => 'Ibu Fitriani', 'phone' => '085698765432'],
            ['name' => 'Pak Dedi Mulyadi', 'phone' => '081233445566'],
            ['name' => 'Ibu Yulianti', 'phone' => '081399887766'],
            ['name' => 'Pak Rahmat Hidayat', 'phone' => '087711223344'],
            ['name' => 'Bu Sri Rejeki', 'phone' => '081288776655'],
            ['name' => 'Pak Joko Widodo', 'phone' => '085811223344'],
        ];

        $paymentMethods = ['Tunai', 'Tunai', 'QRIS', 'Transfer'];
        $cashiers = [$karyawan1, $karyawan1, $karyawan2, $owner];

        $invoiceCounter = 1;

        // Spread sales over 30 days: Today has 4-6 sales, Yesterday has 3-5 sales, etc.
        for ($daysAgo = 29; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::now()->subDays($daysAgo);

            // Higher volume on weekends and recent days
            $salesCountToday = ($daysAgo === 0) ? rand(4, 6) : (($daysAgo === 1) ? rand(3, 5) : rand(1, 4));

            for ($s = 0; $s < $salesCountToday; $s++) {
                $cashier = $cashiers[array_rand($cashiers)];
                $customer = $customerList[array_rand($customerList)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

                // Choose 1-3 items for this sale
                // 65% chance to buy a Uwinfly or NUV e-bike unit + optional accessory, 35% chance battery/charger/spareparts
                $itemsToBuy = [];
                $rndPattern = rand(1, 10);

                if ($rndPattern <= 6) {
                    // Pattern A: Buy an E-bike (choose from Uwinfly e-bike, NUV e-bike, or Uwinfly retro/roda 3)
                    $ebikePool = array_slice($seededProducts, 0, 30);
                    $availableEbikes = array_values(array_filter($ebikePool, fn ($p) => $p->stock > 0));
                    $ebikeChosen = ! empty($availableEbikes) ? $availableEbikes[array_rand($availableEbikes)] : $seededProducts[0];
                    $itemsToBuy[] = ['product' => $ebikeChosen, 'qty' => 1];

                    // 40% add an accessory (Helm Retro / Jas Hujan / Gembok Alarm / Smart Card NFC)
                    if (rand(1, 10) <= 4) {
                        $accPool = array_slice($seededProducts, 41, 11);
                        $accChosen = $accPool[array_rand($accPool)];
                        $itemsToBuy[] = ['product' => $accChosen, 'qty' => 1];
                    }
                } elseif ($rndPattern <= 8) {
                    // Pattern B: Buy Aki / Baterai / Charger Uwinfly atau NUV
                    $batteryPool = array_slice($seededProducts, 30, 8);
                    $batChosen = $batteryPool[array_rand($batteryPool)];
                    $itemsToBuy[] = ['product' => $batChosen, 'qty' => 1];

                    // Maybe add a smart charger
                    if (rand(1, 10) <= 5) {
                        $charger = $seededProducts[34]; // Smart Charger Uwinfly 48V
                        $itemsToBuy[] = ['product' => $charger, 'qty' => 1];
                    }
                } else {
                    // Pattern C: Spareparts & Ban Uwinfly / NUV
                    $partPool = array_slice($seededProducts, 38, 14);
                    $part1 = $partPool[array_rand($partPool)];
                    $itemsToBuy[] = ['product' => $part1, 'qty' => rand(1, 2)];

                    if (rand(1, 10) <= 5) {
                        $part2 = $partPool[array_rand($partPool)];
                        $itemsToBuy[] = ['product' => $part2, 'qty' => rand(1, 2)];
                    }
                }

                // Calculate subtotal
                $subtotalAmount = 0;
                $validatedItems = [];

                foreach ($itemsToBuy as $item) {
                    $prod = $item['product'];
                    $qty = $item['qty'];
                    $unitPrice = (float) $prod->selling_price;
                    $purchasePrice = (float) ($prod->purchase_price ?: 0);
                    $subtotal = $qty * $unitPrice;
                    $subtotalAmount += $subtotal;

                    $validatedItems[] = [
                        'product' => $prod,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'purchase_price' => $purchasePrice,
                        'subtotal' => $subtotal,
                    ];
                }

                // Occasional discount for loyalty (50.000 - 100.000 on e-bikes)
                $discount = ($subtotalAmount >= 3000000 && rand(1, 5) === 1) ? rand(1, 2) * 50000 : 0;
                $finalAmount = max(0, $subtotalAmount - $discount);

                $cashReceived = null;
                $cashChange = null;
                if ($paymentMethod === 'Tunai') {
                    // Round up to clean currency denomination
                    $roundStep = $finalAmount >= 1000000 ? 100000 : 10000;
                    $cashReceived = ceil($finalAmount / $roundStep) * $roundStep;
                    if ($cashReceived === (float) $finalAmount && rand(1, 3) === 1) {
                        $cashReceived += $roundStep;
                    }
                    $cashChange = max(0, $cashReceived - $finalAmount);
                }

                $invNum = 'PJ-'.$date->format('Ymd').'-'.str_pad($invoiceCounter++, 4, '0', STR_PAD_LEFT);

                $itemNames = collect($validatedItems)->pluck('product.name')->take(2)->implode(', ');
                $count = count($validatedItems);
                $desc = "Penjualan {$count} produk ({$itemNames}".($count > 2 ? ', dll' : '').')';

                $saleTrx = Transaction::create([
                    'business_id' => $business->id,
                    'user_id' => $cashier->id,
                    'category_id' => $catPenjualan->id,
                    'type' => 'masuk',
                    'is_sale' => true,
                    'invoice_number' => $invNum,
                    'customer_name' => $customer['name'],
                    'customer_phone' => $customer['phone'],
                    'amount' => $finalAmount,
                    'discount' => $discount,
                    'transaction_date' => $date->toDateString(),
                    'source' => $customer['name'],
                    'description' => $desc,
                    'payment_method' => $paymentMethod,
                    'cash_received' => $cashReceived,
                    'cash_change' => $cashChange,
                ]);

                foreach ($validatedItems as $entry) {
                    $saleTrx->items()->create([
                        'business_id' => $business->id,
                        'transaction_id' => $saleTrx->id,
                        'product_id' => $entry['product']->id,
                        'product_name' => $entry['product']->name,
                        'quantity' => $entry['quantity'],
                        'unit_price' => $entry['unit_price'],
                        'purchase_price' => $entry['purchase_price'],
                        'subtotal' => $entry['subtotal'],
                    ]);

                    // Audit stock movement
                    StockMovement::create([
                        'business_id' => $business->id,
                        'product_id' => $entry['product']->id,
                        'user_id' => $cashier->id,
                        'type' => 'sale',
                        'quantity' => -$entry['quantity'],
                        'stock_before' => $entry['product']->stock + $entry['quantity'],
                        'stock_after' => $entry['product']->stock,
                        'notes' => "Penjualan #{$invNum}",
                        'reference_id' => $saleTrx->id,
                    ]);
                }
            }
        }
    }
}
