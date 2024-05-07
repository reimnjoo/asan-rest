<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * 
     * 
     */

    // protected $keyType = 'string';
    // public $incrementing = false;

    // protected $casts = [
    //     'id' => 'string'
    // ];

    // public $incrementing = false;

    protected $fillable = [
        'id', 'access_uuid','user_type', 'last_name', 'first_name', 'middle_initial', 'fullname', 'date_of_birth', 'affiliation', 'location', 'email', 'username', 'password', 'profile_image', 'id_type', 'id_image', 'id_submitted_date', 'is_deleted',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
