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

    public function updateStockStatus(): void
    {
        $this->stock_status = match (true) {
            $this->stock_quantity == 0 => 'out_of_stock',
            $this->stock_quantity <= 5 => 'low_stock',
            default => 'in_stock',
        };
        $this->save();
    }
}
