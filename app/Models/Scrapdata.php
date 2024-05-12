<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrapdata extends Model
{
    use HasFactory, Uuids;

    protected $keyType = 'string';
    protected $primaryKey = 'scrap_id';
    public $incrementing = false;

    protected $fillable = [
        'warehouse_id',
        'scrap_category',
        'scrap_name',
        'scrap_volume',
        'scrap_price_per_kg',
        'scrap_total_weight',
        'scrap_stock_count',
        'scrap_image',
        'scrap_bar_color',
        'is_deleted'
    ];

}
