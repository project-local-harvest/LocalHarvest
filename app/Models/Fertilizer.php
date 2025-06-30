<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fertilizer extends Model
{
    protected $fillable = [
       'name', 'description', 'npk_ratio', 'category', 'image_url', 'application_guide'
    ];



    protected static function booted()
    {
        static::creating(function ($fertilizer) {
            $lastId = self::max('id') + 1;
            $fertilizer->fertilizer_id = 'fart-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        });
    }

    public function shopInventories()
    {
        return $this->hasMany(ShopInventory::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_inventories');
    }
}
