<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_uuid', 'subscription_id', 'active_subscription_token', 'expires_at', 'is_active'];

    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }
}
