<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'client_id',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
    ];
}
