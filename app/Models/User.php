<?php

// User Model
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Uuids;

// Import PersonalAccessToken model
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable {

    use HasFactory, Notifiable, HasApiTokens, Uuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_type', 'last_name', 'first_name', 'middle_initial', 'fullname', 'date_of_birth', 'affiliation', 'location', 'email', 'username', 'password', 'profile_image', 'id_address', 'id_type', 'id_image', 'id_submitted_date', 'verification_image', 'verification_status', 'is_deleted',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Define the relationship with personal_access_tokens
    public function tokens() {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }
}
