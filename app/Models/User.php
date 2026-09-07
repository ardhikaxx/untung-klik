<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'username',
        'phone',
        'pin_hash',
        'role',
        'is_active',
        'pin_changed_at',
    ];

    protected $hidden = [
        'pin_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'pin_changed_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function capitalEntries(): HasMany
    {
        return $this->hasMany(CapitalEntry::class);
    }

    public function operationalExpenses(): HasMany
    {
        return $this->hasMany(OperationalExpense::class);
    }

    public function isOwner(): bool
    {
        return in_array($this->role, ['owner', 'admin']);
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    public function getPinAttribute(): string
    {
        return '******';
    }
}
