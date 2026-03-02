<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public $timestamps = false;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'role',
        'access_level',
        'status',
        'must_change_password',
        'profile_photo_path',
        'date_time_created',
        'date_time_updated',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'access_levels' => 'array',
        'must_change_password' => 'boolean',
        'date_time_created' => 'datetime',
        'date_time_updated' => 'datetime',
    ];
}
