<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $primaryKey = 'warehouse_id';

    protected $casts = [
        'warehouse_id' => 'string', // Treat uuid attribute as a string
    ];

    public function owner() 
    {
        // return $this->belongsTo(Owners::class);
        return $this->belongsTo(Owners::class, 'warehouse_owner', 'uuid');
    }
}
