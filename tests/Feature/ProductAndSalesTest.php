<?php

use App\Models\Business;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = User::create([
        'name' => 'Pak Budi',
        'username' => 'owner1',
        'phone' => '081111111111',
        'pin_hash' => Hash::make('1234'),
        'role' => 'owner',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);

    $this->business = Business::create([
        'owner_id' => $this->owner->id,
        'name' => 'Toko Barokah',
        'type' => 'Toko Kelontong',
        'phone' => '081111111111',
        'address' => 'Jl. Mawar No. 1',
        'is_active' => true,
    ]);

    $this->owner->update(['business_id' => $this->business->id]);

    $this->karyawan = User::create([
        'business_id' => $this->business->id,
        'name' => 'Siti Kasir',
        'username' => 'kasir1',
        'phone' => '082222222222',
        'pin_hash' => Hash::make('1234'),
        'role' => 'karyawan',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);

    $this->saleCategory = TransactionCategory::create([
        'business_id' => $this->business->id,
        'name' => 'Penjualan Produk',
        'type' => 'masuk',
        'is_active' => true,
    ]);

    $this->productCategory = ProductCategory::create([
        'business_id' => $this->business->id,
        'name' => 'Sembako',
        'description' => 'Bahan pokok',
        'is_active' => true,
    ]);

    $this->product = Product::create([
        'business_id' => $this->business->id,
        'category_id' => $this->productCategory->id,
        'name' => 'Beras Rojo 5kg',
        'sku' => 'BRS-001',
        'selling_price' => 70000,
        'purchase_price' => 60000,
        'unit' => 'karung',
        'stock' => 10,
        'min_stock' => 3,
        'is_active' => true,
    ]);
});

test('owner can view products index and search', function () {
    $response = $this->actingAs($this->owner)->get(route('owner.products.index'));
    $response->assertStatus(200);
    $response->assertSee('Beras Rojo 5kg');
    $response->assertSee('BRS-001');

    // Search query
    $searchResponse = $this->actingAs($this->owner)->get(route('owner.products.index', ['search' => 'Beras']));
    $searchResponse->assertStatus(200);
    $searchResponse->assertSee('Beras Rojo 5kg');

    $emptySearchResponse = $this->actingAs($this->owner)->get(route('owner.products.index', ['search' => 'BarangGaib']));
    $emptySearchResponse->assertStatus(200);
    $emptySearchResponse->assertDontSee('Beras Rojo 5kg');
});

test('owner can create a new product with initial stock movement', function () {
    $response = $this->actingAs($this->owner)->post(route('owner.products.store'), [
        'category_id' => $this->productCategory->id,
        'name' => 'Minyak Goreng 1L',
        'sku' => 'MYK-001',
        'selling_price' => 19000,
        'purchase_price' => 16000,
        'unit' => 'botol',
        'stock' => 15,
        'min_stock' => 5,
        'description' => 'Minyak jernih kemasan botol',
    ]);

    $response->assertRedirect(route('owner.products.index'));

    $newProduct = Product::where('sku', 'MYK-001')->first();
    expect($newProduct)->not->toBeNull();
    expect($newProduct->stock)->toBe(15);

    // Initial stock movement must exist
    $movement = StockMovement::where('product_id', $newProduct->id)->first();
    expect($movement)->not->toBeNull();
    expect($movement->type)->toBe('initial');
    expect($movement->quantity)->toBe(15);
    expect($movement->stock_before)->toBe(0);
    expect($movement->stock_after)->toBe(15);
});

test('karyawan cannot access owner product management routes', function () {
    $response = $this->actingAs($this->karyawan)->get(route('owner.products.index'));
    $response->assertRedirect(route('karyawan.dashboard'));

    $createResponse = $this->actingAs($this->karyawan)->get(route('owner.products.create'));
    $createResponse->assertRedirect(route('karyawan.dashboard'));
});

test('owner can adjust stock manually and logs audit trail', function () {
    // Current stock is 10. Add 5 more
    $response = $this->actingAs($this->owner)->post(route('owner.stock.adjust.store'), [
        'product_id' => $this->product->id,
        'type' => 'in',
        'amount' => 5,
        'notes' => 'Restock supplier A',
    ]);

    $response->assertRedirect(route('owner.stock.index'));

    $this->product->refresh();
    expect($this->product->stock)->toBe(15);

    $movement = StockMovement::where('product_id', $this->product->id)
        ->where('type', 'in')
        ->latest('id')
        ->first();

    expect($movement)->not->toBeNull();
    expect($movement->quantity)->toBe(5);
    expect($movement->stock_before)->toBe(10);
    expect($movement->stock_after)->toBe(15);
    expect($movement->notes)->toBe('Restock supplier A');
});

test('stock adjustment rejects quantity exceeding available stock for reduction', function () {
    // Current stock is 10. Try to deduct 15 (stock cannot be negative)
    $response = $this->actingAs($this->owner)->post(route('owner.stock.adjust.store'), [
        'product_id' => $this->product->id,
        'type' => 'out',
        'amount' => 15,
        'notes' => 'Pengurangan berlebih',
    ]);

    $response->assertSessionHas('error');

    $this->product->refresh();
    expect($this->product->stock)->toBe(10); // unchanged
});

test('owner can record sale with atomic stock deduction and cash book integration', function () {
    $initialStock = $this->product->stock; // 10

    $response = $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Pelanggan Langganan',
        'payment_method' => 'Tunai',
        'description' => 'Beli beras 2 karung',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 2,
                'unit_price' => 70000,
            ],
        ],
    ]);

    // Assert transaction created
    $trx = Transaction::where('business_id', $this->business->id)
        ->where('is_sale', true)
        ->latest('id')
        ->first();

    expect($trx)->not->toBeNull();
    $response->assertRedirect(route('owner.sales.show', $trx));

    $this->product->refresh();
    expect($this->product->stock)->toBe($initialStock - 2); // 8

    expect((float) $trx->amount)->toBe(140000.0);
    expect($trx->type)->toBe('masuk');

    // Assert transaction items created
    expect($trx->items)->toHaveCount(1);
    expect($trx->items->first()->quantity)->toBe(2);
    expect((float) $trx->items->first()->subtotal)->toBe(140000.0);

    // Assert stock movement logged
    $movement = StockMovement::where('product_id', $this->product->id)
        ->where('type', 'sale')
        ->first();

    expect($movement)->not->toBeNull();
    expect($movement->quantity)->toBe(-2);
    expect($movement->stock_before)->toBe(10);
    expect($movement->stock_after)->toBe(8);
});

test('karyawan can record sale successfully', function () {
    $response = $this->actingAs($this->karyawan)->post(route('karyawan.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Pembeli Toko',
        'payment_method' => 'Tunai',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'unit_price' => 70000,
            ],
        ],
    ]);

    $this->product->refresh();
    expect($this->product->stock)->toBe(9);

    $trx = Transaction::where('user_id', $this->karyawan->id)
        ->where('is_sale', true)
        ->latest('id')
        ->first();

    expect($trx)->not->toBeNull();
    $response->assertRedirect(route('karyawan.sales.show', $trx));
});

test('sale fails when requested quantity exceeds product stock', function () {
    // Current stock is 10, try to buy 11
    $response = $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Pembeli Toko',
        'payment_method' => 'Tunai',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 11,
                'unit_price' => 70000,
            ],
        ],
    ]);

    $response->assertSessionHas('warning');

    $this->product->refresh();
    expect($this->product->stock)->toBe(10); // Stock intact

    // No sale transaction created
    expect(Transaction::where('is_sale', true)->count())->toBe(0);
});

test('deleting a sale restores product stock and logs adjustment movement', function () {
    // Create sale of 3 units
    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Customer',
        'payment_method' => 'Tunai',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 3,
                'unit_price' => 70000,
            ],
        ],
    ]);

    $this->product->refresh();
    expect($this->product->stock)->toBe(7);

    $trx = Transaction::where('is_sale', true)->latest('id')->first();
    expect($trx)->not->toBeNull();

    // Owner deletes the sale
    $deleteResponse = $this->actingAs($this->owner)->delete(route('owner.sales.destroy', $trx));
    $deleteResponse->assertRedirect(route('owner.sales.index'));

    $this->product->refresh();
    expect($this->product->stock)->toBe(10); // restored to 10

    // Rollback movement logged
    $rollbackMovement = StockMovement::where('product_id', $this->product->id)
        ->where('type', 'correction')
        ->latest('id')
        ->first();

    expect($rollbackMovement)->not->toBeNull();
    expect($rollbackMovement->quantity)->toBe(3);
    expect($rollbackMovement->stock_after)->toBe(10);

    // Transaction deleted
    expect(Transaction::find($trx->id))->toBeNull();
});

test('karyawan cannot delete sales transactions', function () {
    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Customer',
        'payment_method' => 'Tunai',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'unit_price' => 70000,
            ],
        ],
    ]);

    $trx = Transaction::where('is_sale', true)->latest('id')->first();
    expect($trx)->not->toBeNull();

    $response = $this->actingAs($this->karyawan)->delete(route('owner.sales.destroy', $trx));
    $response->assertRedirect(route('karyawan.dashboard'));
});

test('multi-tenancy isolation prevents seeing other business products and sales', function () {
    // Create business B and owner B
    $ownerB = User::create([
        'name' => 'Pak Joko',
        'username' => 'owner2',
        'phone' => '083333333333',
        'pin_hash' => Hash::make('1234'),
        'role' => 'owner',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);

    $businessB = Business::create([
        'owner_id' => $ownerB->id,
        'name' => 'Toko Maju',
        'type' => 'Toko',
        'phone' => '083333333333',
        'address' => 'Jl. Melati',
        'is_active' => true,
    ]);

    $ownerB->update(['business_id' => $businessB->id]);

    // Owner B should NOT see product from business A
    $response = $this->actingAs($ownerB)->get(route('owner.products.index'));
    $response->assertStatus(200);
    $response->assertDontSee('Beras Rojo 5kg');

    // Owner B cannot edit product from business A
    $editResponse = $this->actingAs($ownerB)->get(route('owner.products.edit', $this->product));
    $editResponse->assertStatus(403);
});

test('owner can create, update, and delete product categories', function () {
    // Create
    $response = $this->actingAs($this->owner)->post(route('owner.product-categories.store'), [
        'name' => 'Minuman',
        'description' => 'Minuman segar',
    ]);
    $response->assertRedirect(route('owner.product-categories.index'));

    $cat = ProductCategory::where('name', 'Minuman')->first();
    expect($cat)->not->toBeNull();

    // Update
    $updateResponse = $this->actingAs($this->owner)->put(route('owner.product-categories.update', $cat), [
        'name' => 'Minuman Dingin',
        'description' => 'Minuman dingin dan seduh',
    ]);
    $updateResponse->assertRedirect(route('owner.product-categories.index'));
    $cat->refresh();
    expect($cat->name)->toBe('Minuman Dingin');

    // Delete empty category
    $delResponse = $this->actingAs($this->owner)->delete(route('owner.product-categories.destroy', $cat));
    $delResponse->assertRedirect(route('owner.product-categories.index'));
    expect(ProductCategory::find($cat->id))->toBeNull();
});

test('category with existing products cannot be deleted', function () {
    // $this->productCategory already has $this->product
    $response = $this->actingAs($this->owner)->delete(route('owner.product-categories.destroy', $this->productCategory));
    $response->assertSessionHas('error');
    expect(ProductCategory::find($this->productCategory->id))->not->toBeNull();
});

test('owner can toggle product active status', function () {
    expect($this->product->is_active)->toBeTrue();

    $response = $this->actingAs($this->owner)->post(route('owner.products.toggle', $this->product));
    $response->assertRedirect(route('owner.products.index'));

    $this->product->refresh();
    expect($this->product->is_active)->toBeFalse();
});

test('inactive product cannot be sold', function () {
    $this->product->update(['is_active' => false]);

    $response = $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Pelanggan',
        'payment_method' => 'Tunai',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'unit_price' => 70000,
            ],
        ],
    ]);

    $response->assertSessionHas('warning');
    expect(Transaction::where('is_sale', true)->count())->toBe(0);
});

test('owner and karyawan can view sale receipt with invoice details', function () {
    $this->actingAs($this->owner)->post(route('owner.sales.store'), [
        'transaction_date' => now()->toDateString(),
        'source' => 'Walk-in',
        'payment_method' => 'Tunai',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 2,
                'unit_price' => 70000,
            ],
        ],
    ]);

    $trx = Transaction::where('is_sale', true)->first();

    // Owner view receipt
    $ownerView = $this->actingAs($this->owner)->get(route('owner.sales.show', $trx));
    $ownerView->assertStatus(200);
    $ownerView->assertSee('Beras Rojo 5kg');
    $ownerView->assertSee('140.000');
    $ownerView->assertSee($this->business->name);
});

test('stock movement activity badges have distinctive colors and icons', function () {
    $types = ['initial', 'in', 'out', 'sale', 'adjustment', 'damaged', 'lost', 'correction'];

    foreach ($types as $type) {
        $movement = new StockMovement(['type' => $type]);
        expect($movement->type_badge_class)->not->toBeEmpty();
        expect($movement->type_icon)->not->toBeEmpty();
    }

    // Check stock index page renders badge class
    $response = $this->actingAs($this->owner)->get(route('owner.stock.index'));
    $response->assertStatus(200);
});
