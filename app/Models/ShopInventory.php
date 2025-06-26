<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'fertilizer_id',
        'stock_quantity',
        'stock_status',
        'price_per_unit',
    ];
    public function fertilizer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fertilizer::class);
    }
}
