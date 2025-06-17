<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fertilizer extends Model
{
    protected $fillable = [
        'name', 'description', 'npk_ratio', 'category', 'image_url', 'application_guide'
    ];
}
