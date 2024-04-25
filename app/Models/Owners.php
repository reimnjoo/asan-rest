<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owners extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $casts = [
        'uuid' => 'string', // Treat uuid attribute as a string
    ];

    public function warehouses() {
        return $this->hasMany(Warehouse::class);
    }

    public function getFullNameAttribute()
    {
        // Concatenate the first name, last name, and middle initial with a space
        return trim("{$this->first_name} {$this->middle_initial} {$this->last_name}");
    }
}

