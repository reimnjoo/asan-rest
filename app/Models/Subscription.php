<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, Uuids;

    protected $keyType = 'string';
    protected $primaryKey = 'subscription_id';
    public $incrementing = false;

    protected $fillable = [
        'client_id',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
    ];
}
