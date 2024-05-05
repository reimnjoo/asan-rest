<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id', 'warehouse_owner_id','warehouse_name', 'warehouse_owner', 'warehouse_location'
    ];
}
