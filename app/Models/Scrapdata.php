<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrapdata extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'scrap_id',
        'scrap_category',
        'scrap_name',
        'scrap_volume',
        'scrap_price_per_kg',
        'scrap_stock_count',
        'scrap_image',
        'is_deleted'
    ];

}
