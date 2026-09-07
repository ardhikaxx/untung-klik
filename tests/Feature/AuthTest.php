<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('login requires username and 4-digit pin with localized indonesian messages', function () {
    $response = $this->post(route('login.post'), [
        'username' => '',
        'pin' => '12',
    ]);

    $response->assertSessionHasErrors([
        'username' => 'Username wajib diisi.',
        'pin' => 'PIN harus terdiri dari 4 digit angka.',
    ]);
});

test('login succeeds with correct credentials', function () {
    $user = User::create([
        'name' => 'Budi Santoso',
        'username' => 'owner',
        'phone' => '081234567890',
        'pin_hash' => Hash::make('2222'),
        'role' => 'owner',
        'is_active' => true,
        'pin_changed_at' => now(),
    ]);

    Business::create([
        'owner_id' => $user->id,
        'name' => 'UD Untung Makmur E-Bike',
        'type' => 'retail',
    ]);

    $response = $this->post(route('login.post'), [
        'username' => 'owner',
        'pin' => '2222',
    ]);

    $response->assertRedirect(route('owner.dashboard'));
    $this->assertAuthenticatedAs($user);
});
