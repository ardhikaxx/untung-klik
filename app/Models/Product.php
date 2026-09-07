<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'category_id',
        'name',
        'sku',
        'selling_price',
        'purchase_price',
        'unit',
        'stock',
        'min_stock',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->latest('id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock && $this->stock > 0;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out';
        }
        if ($this->isLowStock()) {
            return 'low';
        }

        return 'safe';
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'out' => 'Stok Habis',
            'low' => 'Stok Menipis',
            default => 'Stok Aman',
        };
    }

    public function getStockStatusBadgeClassAttribute(): string
    {
        return match ($this->stock_status) {
            'out' => 'badge-danger',
            'low' => 'badge-warning',
            default => 'badge-success',
        };
    }
}
