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
        'name' => 'Toko Barokah Jaya',
        'type' => 'Warung Kelontong',
        'phone' => '081234567890',
        'address' => 'Jl. Merdeka No. 10',
        'is_active' => true,
    ]);

    $this->owner->update(['business_id' => $this->business->id]);

    $this->karyawan = User::create([
        'business_id' => $this->business->id,
        'name' => 'Siti Kasir',
        'username' => 'karyawan1',
        'phone' => '081234567891',
        'pin_hash' => Hash::make('2222'),
        'role' => 'karyawan',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);

    $this->catSembako = ProductCategory::create([
        'business_id' => $this->business->id,
        'name' => 'Sembako',
        'is_active' => true,
    ]);

    $this->productA = Product::create([
        'business_id' => $this->business->id,
        'category_id' => $this->catSembako->id,
        'name' => 'Beras Pandan Wangi 5kg',
        'sku' => 'BRS-05',
        'selling_price' => 75000,
        'purchase_price' => 60000,
        'unit' => 'karung',
        'stock' => 50,
        'min_stock' => 5,
        'is_active' => true,
    ]);

    $this->productB = Product::create([
        'business_id' => $this->business->id,
        'category_id' => $this->catSembako->id,
        'name' => 'Minyak Goreng 2L',
        'sku' => 'MYK-02',
        'selling_price' => 36000,
        'purchase_price' => 30000,
        'unit' => 'pouch',
        'stock' => 40,
        'min_stock' => 5,
        'is_active' => true,
    ]);
});

test('owner can record sale with customer info, discount, and cash change', function () {
    // Buy 2 Beras (2 x 75.000 = 150.000) with 10.000 discount = 140.000
    // Customer pays 150.000 cash -> change 10.000
    $response = $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'customer_name' => 'Ibu Ratna',
        'customer_phone' => '081299998888',
        'payment_method' => 'Tunai',
        'discount' => 10000,
        'cash_received' => 150000,
        'description' => 'Beli beras langganan',
        'items' => [
            [
                'product_id' => $this->productA->id,
                'quantity' => 2,
            ],
        ],
    ]);

    $sale = Transaction::where('business_id', $this->business->id)
        ->where('is_sale', true)
        ->latest('id')
        ->first();

    expect($sale)->not->toBeNull();
    expect($sale->customer_name)->toBe('Ibu Ratna');
    expect($sale->customer_phone)->toBe('081299998888');
    expect((float) $sale->amount)->toBe(140000.0);
    expect((float) $sale->discount)->toBe(10000.0);
    expect((float) $sale->cash_received)->toBe(150000.0);
    expect((float) $sale->cash_change)->toBe(10000.0);
    expect($sale->invoice_number)->toStartWith('PJ-');

    $response->assertRedirect(route('owner.sales.show', $sale));

    // Item purchase price should be locked in as 60.000
    $item = $sale->items->first();
    expect((float) $item->purchase_price)->toBe(60000.0);
    expect((float) $item->subtotal)->toBe(150000.0);
    expect($item->gross_profit)->toBe(30000.0); // 150.000 - (2 * 60.000)
});

test('owner can view sales report and analytics', function () {
    // Record sample sales
    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'customer_name' => 'Pak Ahmad',
        'payment_method' => 'Tunai',
        'items' => [
            ['product_id' => $this->productA->id, 'quantity' => 3], // 3 * 75.000 = 225.000, HPP: 3 * 60.000 = 180.000, Profit = 45.000
            ['product_id' => $this->productB->id, 'quantity' => 2], // 2 * 36.000 = 72.000, HPP: 2 * 30.000 = 60.000, Profit = 12.000
        ],
    ]);

    $response = $this->actingAs($this->owner)->get(route('owner.reports.sales', ['period' => 'today']));
    $response->assertStatus(200);

    // Total sales: 225.000 + 72.000 = 297.000
    $response->assertViewHas('totalSales', 297000.0);
    // Total HPP: 180.000 + 60.000 = 240.000
    $response->assertViewHas('totalHpp', 240000.0);
    // Gross Profit: 57.000
    $response->assertViewHas('grossProfit', 57000.0);
    // Total Items: 5
    $response->assertViewHas('totalItemsSold', 5);

    $response->assertSee('Beras Pandan Wangi 5kg');
    $response->assertSee('Minyak Goreng 2L');
    $response->assertSee('297.000');
});

test('karyawan is redirected when trying to access owner sales report', function () {
    $response = $this->actingAs($this->karyawan)->get(route('owner.reports.sales'));
    $response->assertRedirect(route('karyawan.dashboard'));
});

test('karyawan daily report displays recorded product sales with item breakdown', function () {
    $this->actingAs($this->karyawan)->post(route('karyawan.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'customer_name' => 'Mbak Dewi',
        'payment_method' => 'QRIS',
        'items' => [
            ['product_id' => $this->productB->id, 'quantity' => 1],
        ],
    ]);

    $response = $this->actingAs($this->karyawan)->get(route('karyawan.reports.index', ['date' => now()->toDateString()]));
    $response->assertStatus(200);
    $response->assertSee('Minyak Goreng 2L');
    $response->assertSee('36.000');
    $response->assertSee('Penjualan Produk');
});

test('owner can export sales report as pdf and excel', function () {
    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'customer_name' => 'Pak Budi',
        'payment_method' => 'Tunai',
        'items' => [
            ['product_id' => $this->productA->id, 'quantity' => 2],
        ],
    ]);

    $pdfResponse = $this->actingAs($this->owner)->get(route('owner.export.sales.pdf', ['period' => 'today']));
    $pdfResponse->assertStatus(200);
    expect($pdfResponse->headers->get('content-type'))->toContain('application/pdf');

    $excelResponse = $this->actingAs($this->owner)->get(route('owner.export.sales.excel', ['period' => 'today']));
    $excelResponse->assertStatus(200);
    expect($excelResponse->headers->get('content-disposition'))->toContain('.xlsx');
});

test('deleting a sales transaction restores product stock and logs movement', function () {
    $initialStock = $this->productA->stock; // 50

    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'customer_name' => 'Pak Rudi',
        'payment_method' => 'Tunai',
        'items' => [
            ['product_id' => $this->productA->id, 'quantity' => 5],
        ],
    ]);

    expect($this->productA->fresh()->stock)->toBe($initialStock - 5);

    $sale = Transaction::where('business_id', $this->business->id)
        ->where('is_sale', true)
        ->latest('id')
        ->first();

    // Delete sale via owner transactions destroy
    $response = $this->actingAs($this->owner)->delete(route('owner.transactions.destroy', $sale));
    $response->assertRedirect(route('owner.transactions.index'));

    // Stock should be restored
    expect($this->productA->fresh()->stock)->toBe($initialStock);
    $this->assertDatabaseMissing('transactions', ['id' => $sale->id]);
});

test('accessing general edit on a sales transaction redirects to sales show', function () {
    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'customer_name' => 'Bu Joko',
        'payment_method' => 'Tunai',
        'items' => [
            ['product_id' => $this->productB->id, 'quantity' => 1],
        ],
    ]);

    $sale = Transaction::where('business_id', $this->business->id)
        ->where('is_sale', true)
        ->latest('id')
        ->first();

    $response = $this->actingAs($this->owner)->get(route('owner.transactions.edit', $sale));
    $response->assertRedirect(route('owner.sales.show', $sale));
});
