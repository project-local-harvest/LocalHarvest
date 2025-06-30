<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'shop_name',
        'contact_number',
        'address',
        'owner_picture_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shopInventories()
    {
        return $this->hasMany(ShopInventory::class);
    }

    public function fertilizers()
    {
        return $this->belongsToMany(Fertilizer::class, 'shop_inventories');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            $latestId = self::max('id') ?? 0;
            $nextId = 100 + $latestId + 1;
            $year = now()->year;
            $shop->shop_serial_number = "MHP{$year}{$nextId}";
        });
    }

}
