<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name',
        'hsn',
        'pack',
        'quantity',
        'free_quantity',
        'mrp',
        'rate',
        'discount',
        'discount_percentage',
        'tax',
        'gst_percentage',
        'gst_amount',
        'net_amount',
        'line_total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'free_quantity' => 'integer',
        'mrp' => 'decimal:2',
        'rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Get the invoice that owns the item
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the product for this item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
