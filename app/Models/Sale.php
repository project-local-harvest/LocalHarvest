<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'shop_id', 'receipt_no',
        'customer_name', 'customer_phone', 'salesperson_name',
        'discount_percent', 'gross_amount', 'net_amount'
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    { return $this->hasMany(SaleItem::class); }
    public function shop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    { return $this->belongsTo(Shop::class);   }
}
