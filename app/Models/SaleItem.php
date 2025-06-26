<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'fertilizer_id',
        'quantity', 'unit_price', 'subtotal'
    ];

    public function fertilizer() { return $this->belongsTo(Fertilizer::class); }
}
