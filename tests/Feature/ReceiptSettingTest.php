<?php

use App\Models\Business;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = User::create([
        'name' => 'Budi Santoso',
        'username' => 'owner',
        'phone' => '081234567890',
        'pin_hash' => Hash::make('2222'),
        'role' => 'owner',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);

    $this->business = Business::create([
        'owner_id' => $this->owner->id,
        'name' => 'Galeri E-Bike Uwinfly & NUV',
        'type' => 'Dealer Resmi Sepeda & Motor Listrik',
        'phone' => '081234567890',
        'address' => 'Jl. Soekarno-Hatta No. 210, Bandung',
        'receipt_footer' => 'Terima Kasih Atas Kunjungan Anda!',
        'receipt_note' => 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.',
        'is_active' => true,
    ]);

    $this->owner->update(['business_id' => $this->business->id]);

    $this->karyawan = User::create([
        'business_id' => $this->business->id,
        'name' => 'Siti Rahayu',
        'username' => 'karyawan1',
        'phone' => '081234567891',
        'pin_hash' => Hash::make('2222'),
        'role' => 'karyawan',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);
});

test('owner can access receipt settings page', function () {
    $response = $this->actingAs($this->owner)->get(route('owner.receipt.index'));

    $response->assertStatus(200);
    $response->assertSee('Galeri E-Bike Uwinfly & NUV');
    $response->assertSee('Jl. Soekarno-Hatta No. 210, Bandung');
    $response->assertSee('081234567890');
    $response->assertSee('Pengaturan Bagian Nota & Struk');
});

test('owner can update receipt settings successfully', function () {
    $response = $this->actingAs($this->owner)->put(route('owner.receipt.update'), [
        'name' => 'Galeri E-Bike Uwinfly & NUV Baru',
        'type' => 'Pusat Sepeda Listrik Terlengkap',
        'phone' => '089876543210',
        'address' => 'Jl. Asia Afrika No. 100, Bandung',
        'receipt_footer' => 'Terima Kasih & Selamat Berbelanja Kembali!',
        'receipt_note' => 'Garansi baterai & motor listrik 1 tahun resmi.',
    ]);

    $response->assertRedirect(route('owner.receipt.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('businesses', [
        'id' => $this->business->id,
        'name' => 'Galeri E-Bike Uwinfly & NUV Baru',
        'type' => 'Pusat Sepeda Listrik Terlengkap',
        'phone' => '089876543210',
        'address' => 'Jl. Asia Afrika No. 100, Bandung',
        'receipt_footer' => 'Terima Kasih & Selamat Berbelanja Kembali!',
        'receipt_note' => 'Garansi baterai & motor listrik 1 tahun resmi.',
    ]);
});

test('receipt setting requires name field', function () {
    $response = $this->actingAs($this->owner)->put(route('owner.receipt.update'), [
        'name' => '',
        'phone' => '081234567890',
        'address' => 'Jl. Soekarno-Hatta',
    ]);

    $response->assertSessionHasErrors('name');
});

test('karyawan cannot access receipt settings page', function () {
    $response = $this->actingAs($this->karyawan)->get(route('owner.receipt.index'));

    $response->assertRedirect(route('karyawan.dashboard'));
    $response->assertSessionHas('error');
});

test('guest is redirected to login when accessing receipt settings', function () {
    $response = $this->get(route('owner.receipt.index'));

    $response->assertRedirect(route('login'));
});

test('sales invoice show page renders configured store details', function () {
    $sale = Transaction::create([
        'business_id' => $this->business->id,
        'user_id' => $this->owner->id,
        'invoice_number' => 'INV-20260908-0001',
        'type' => 'masuk',
        'amount' => 500000,
        'subtotal' => 500000,
        'discount' => 0,
        'transaction_date' => now()->toDateString(),
        'payment_method' => 'cash',
        'is_sale' => true,
        'customer_name' => 'Pak Budi',
    ]);

    $response = $this->actingAs($this->owner)->get(route('owner.sales.show', $sale));

    $response->assertStatus(200);
    $response->assertSee('Galeri E-Bike Uwinfly & NUV');
    $response->assertSee('Jl. Soekarno-Hatta No. 210, Bandung');
    $response->assertSee('081234567890');
    $response->assertSee('Terima Kasih Atas Kunjungan Anda!');
});

test('receipt preview displays real products from database and reset to app defaults', function () {
    $category = ProductCategory::create([
        'business_id' => $this->business->id,
        'name' => 'Sepeda Listrik',
        'is_active' => true,
    ]);

    $product = Product::create([
        'business_id' => $this->business->id,
        'category_id' => $category->id,
        'name' => 'Uwinfly D7S Super Red',
        'sku' => 'UWF-D7S-RED-TEST',
        'selling_price' => 3950000,
        'purchase_price' => 3300000,
        'unit' => 'unit',
        'stock' => 10,
        'min_stock' => 2,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->owner)->get(route('owner.receipt.index'));

    $response->assertStatus(200);
    $response->assertSee('Uwinfly D7S Super Red');
    $response->assertSee('Rp 3.950.000');
    $response->assertSee('Reset Bawaan Aplikasi');
    $response->assertSee('Template Galeri E-Bike');
    $response->assertSee('Untung Klik');
});
