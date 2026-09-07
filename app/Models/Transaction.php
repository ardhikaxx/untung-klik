<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'category_id',
        'type',
        'is_sale',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'amount',
        'discount',
        'transaction_date',
        'source',
        'description',
        'payment_method',
        'cash_received',
        'cash_change',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'cash_change' => 'decimal:2',
        'is_sale' => 'boolean',
        'transaction_date' => 'date',
    ];

    public function getFormattedInvoiceNumberAttribute(): string
    {
        return $this->invoice_number ?: ('PJ-'.str_pad($this->id, 5, '0', STR_PAD_LEFT));
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->amount + (float) $this->discount;
    }

    public function getTotalHppAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->quantity * ($item->purchase_price ?: 0);
        });
    }

    public function getGrossProfitAttribute(): float
    {
        return (float) $this->amount - $this->total_hpp;
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
