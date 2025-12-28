<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'eway_bill',
        'mr_no',
        's_man',
        'customer_id', // Only store customer_id, get customer data via relationship
        'subtotal',
        'discount',
        'tax',
        'cgst_percentage',
        'cgst_amount',
        'sgst_percentage',
        'sgst_amount',
        'additional_amount',
        'round_off',
        'grand_total',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'cgst_percentage' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_percentage' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'additional_amount' => 'decimal:2',
        'round_off' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the invoice
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all items for this invoice
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
