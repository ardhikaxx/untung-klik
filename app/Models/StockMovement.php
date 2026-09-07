<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'product_id',
        'user_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'notes',
        'reference_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'initial' => 'Stok Awal',
            'in' => 'Penambahan Stok',
            'out' => 'Pengurangan Stok',
            'sale' => 'Penjualan',
            'adjustment' => 'Penyesuaian',
            'damaged' => 'Barang Rusak',
            'lost' => 'Barang Hilang',
            'correction' => 'Koreksi Stok',
            default => ucfirst($this->type),
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'initial', 'in' => 'badge-success',
            'sale' => 'badge-info',
            'damaged', 'lost' => 'badge-danger',
            'adjustment', 'correction' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }
}
