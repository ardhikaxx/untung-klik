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
            'initial' => 'badge-stok-initial bg-primary text-white',
            'in' => 'badge-stok-in bg-success text-white',
            'out' => 'badge-stok-out bg-warning text-white',
            'sale' => 'badge-stok-sale bg-info text-white',
            'adjustment' => 'badge-stok-adjustment bg-warning text-white',
            'damaged' => 'badge-stok-damaged bg-danger text-white',
            'lost' => 'badge-stok-lost bg-dark text-white',
            'correction' => 'badge-stok-correction bg-secondary text-white',
            default => 'badge-secondary bg-secondary text-white',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'initial' => 'fas fa-box',
            'in' => 'fas fa-arrow-down',
            'out' => 'fas fa-arrow-up',
            'sale' => 'fas fa-shopping-cart',
            'adjustment' => 'fas fa-sliders-h',
            'damaged' => 'fas fa-heart-crack',
            'lost' => 'fas fa-question-circle',
            'correction' => 'fas fa-wrench',
            default => 'fas fa-circle',
        };
    }
}
