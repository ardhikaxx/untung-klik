<?php

use App\Models\Business;
use App\Models\CapitalEntry;
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
        'name' => 'Warung Berkah',
        'type' => 'Warung Kelontong',
        'phone' => '081234567890',
        'address' => 'Jl. Merdeka No. 45, Bandung',
        'is_active' => true,
    ]);

    $this->owner->update(['business_id' => $this->business->id]);

    CapitalEntry::create([
        'business_id' => $this->business->id,
        'user_id' => $this->owner->id,
        'amount' => 5000000,
        'entry_date' => now()->subMonths(2),
        'source' => 'Tabungan Pribadi',
        'description' => 'Modal awal warung',
    ]);

    CapitalEntry::create([
        'business_id' => $this->business->id,
        'user_id' => $this->owner->id,
        'amount' => 3500000,
        'entry_date' => now()->subMonth(),
        'source' => 'Pinjaman Bank',
        'description' => 'Tambahan modal stok',
    ]);
});

test('owner dashboard displays total modal stat correctly regardless of period', function () {
    $response = $this->actingAs($this->owner)
        ->get(route('owner.dashboard', ['period' => 'month']));

    $response->assertStatus(200);
    $response->assertViewHas('totalCapital', 8500000.0);
    $response->assertSee('8.500.000');
});

test('owner report page displays total modal stat correctly', function () {
    $response = $this->actingAs($this->owner)
        ->get(route('owner.reports.index', ['period' => 'month']));

    $response->assertStatus(200);
    $response->assertViewHas('totalCapital', 8500000.0);
    $response->assertSee('8.500.000');
});
